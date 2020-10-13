<?php
declare(strict_types=1);

namespace Demo\Controllers;

use Demo\Models\Renderer;
use Demo\Repository\Api\LoggerRepositoryInterface;
use Demo\Repository\Api\UserAccountRepositoryInterface;
use Demo\Repository\Api\UserAddressRepositoryInterface;
use Demo\Repository\Api\UserPhoneRepositoryInterface;
use Demo\Repository\Api\UserRepositoryInterface;
use Exception;
use Pecee\Controllers\IResourceController;
use Symfony\Component\Translation\Exception\ExceptionInterface;

class UsersController implements IResourceController
{
    /**
     * @var UserRepositoryInterface
     */
    private $usersRepository;

    /**
     * @var UserAccountRepositoryInterface
     */
    private $userAccountRepository;

    /**
     * @var UserPhoneRepositoryInterface
     */
    private $userPhoneRepository;

    /**
     * @var UserAddressRepositoryInterface
     */
    private $userAddressRepository;

    /**
     * @var Renderer
     */
    private $twig;
    /**
     * @var LoggerRepositoryInterface
     */
    private $loggerRepository;

    /**
     * UsersController constructor.
     * @param UserRepositoryInterface $usersRepository
     * @param UserAccountRepositoryInterface $userAccountRepository
     * @param UserPhoneRepositoryInterface $userPhoneRepository
     * @param UserAddressRepositoryInterface $userAddressRepository
     * @param Renderer $twig
     * @param LoggerRepositoryInterface $loggerRepository
     */
    public function __construct(UserRepositoryInterface $usersRepository,
                                UserAccountRepositoryInterface $userAccountRepository,
                                UserPhoneRepositoryInterface $userPhoneRepository,
                                UserAddressRepositoryInterface $userAddressRepository,
                                Renderer $twig,
                                LoggerRepositoryInterface $loggerRepository)
    {
        $this->usersRepository = $usersRepository;
        $this->userAccountRepository = $userAccountRepository;
        $this->userPhoneRepository = $userPhoneRepository;
        $this->userAddressRepository = $userAddressRepository;
        $this->twig = $twig;
        $this->loggerRepository = $loggerRepository;
    }

    /**
     * @return string|null
     */
    public function index(): ?string
    {
        $userList = $this->usersRepository->list();
        return response()->json(['index' => $userList]);
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function show($id): ?string
    {
        $authUser = getUser();
        $user = $this->usersRepository->getFirstUserDecrypted('id',$authUser->id);
        $phone = $this->userPhoneRepository->getPhoneListDecrypted('user_id',$authUser->id);
        $address = $this->userAddressRepository->getAddressListDecrypted('user_id',$authUser->id);
        $account = $this->userAccountRepository->getUserAccountDecrypted('user_id',$authUser->id);

        return response()->json(['user' => $user,
                                 'phone' => $phone,
                                 'addres' => $address,
                                 'account' => $account]);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderShow()
    {
        $authUser = getUser();
        $user = $this->usersRepository->getFirstUserDecrypted('id',$authUser->id);
        $phone = $this->userPhoneRepository->getPhoneListDecrypted('user_id',$authUser->id);
        $address = $this->userAddressRepository->getAddressListDecrypted('user_id',$authUser->id);
        $account = $this->userAccountRepository->getUserAccountDecrypted('user_id',$authUser->id);

        $fulldata = array_merge($user, $phone, $address, $account);

        return $this->twig->render()->render('/Users/Index.html',["id" =>  $authUser->id]);
    }

    /**
     * @return string|null
     */
    public function create(): ?string
    {
        return response()->json([
            'blade' => 'create user blade'
        ]);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderCreate()
    {
        return $this->twig->render()->render('/Users/Create.html',[null]);
    }

    /**
     * @return string|null
     */
    public function store(): ?string
    {
        try{
            $request = input()->all();
            $create = [ "email"=> xorEncrypt($request['email']),
                        "password"=> xorEncrypt($request['password']),
                        "name"=> xorEncrypt($request['name']),
                        "identification"=> xorEncrypt($request['identification']),
                        "registration"=> xorEncrypt($request['registration']),
                        "birth_date"=> xorEncrypt($request['birth_date'])];
            $user = $this->usersRepository->create($create);

            if(array_key_exists('phone',$request)) {
                if(isset($request['phone'])) {
                    $userPhone = ['user_id' => $user->id,
                                  'phone' => xorEncrypt($request['phone'])];
                    $this->userPhoneRepository->create($userPhone);
                }
            }

            if(array_key_exists('cep',$request) && array_key_exists('address',$request)) {
                if(isset($request['cep']) && isset($request['address'])) {
                    $userAddress = [ 'user_id'=> $user->id,
                        'cep' => xorEncrypt($request['cep']),
                        'address' => xorEncrypt($request['address']),
                        'number' => xorEncrypt($request['number']) ?? 'NONE',
                        'reference' => xorEncrypt($request['reference']) ?? 'NONE',
                        'observation' => xorEncrypt($request['observation']) ?? 'NONE'];
                    $this->userAddressRepository->create($userAddress);
                }
            }

            $account_number = $this->userAccountRepository->createAccountNumber($user);
            $newAccount = [
                "user_id" => $user->id,
                "account_number" => xorEncrypt($account_number),
                "funds" => xorEncrypt("0.00")
            ];

            $account = $this->userAccountRepository->create($newAccount);

            $message = "Created user " . $user->id . ". With account number: " . xorEncrypt($account->account_number,'decrypt');
            $this->loggerRepository->createModelLog("user",$message,200, $request);
            return response()->json([
                'create' => $message
            ]);

        }catch (Exception $ex){

            $message = "Some error occured. More info in => " . $ex->getMessage();
            $this->loggerRepository->createModelLog("user",$message,400);
            throwException($ex);

        }
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function edit($id): ?string
    {
        $user = $this->usersRepository->find($id);
        return response()->json(['edit'=> $user]);
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function update($id): ?string
    {
        try{
            $request = input()->all();
            $update = [ "email"=> xorEncrypt($request['email']),
                "password"=> xorEncrypt($request['password']),
                "name"=> xorEncrypt($request['name']),
                "identification"=> xorEncrypt($request['identification']),
                "registration"=> xorEncrypt($request['registration']),
                "birth_date"=> xorEncrypt($request['birth_date'])];
            $user = $this->usersRepository->update($id, $update);

            $message = "Updated user " . $id;
            $this->loggerRepository->createModelLog("user",$message,200, $request);
            return response()->json([
                'edit' => "Updated user " . $id
            ]);

        }catch(Exception $ex){
            $message = "Some error occured. More info in => " . $ex->getMessage();
            $this->loggerRepository->createModelLog("user",$message,400);
            throwException($ex);
        }
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function destroy($id): ?string
    {
        return response()->json([
            'Opppssss...' => 'Essa acao nao pode ser executada.'
        ]);
    }
}
