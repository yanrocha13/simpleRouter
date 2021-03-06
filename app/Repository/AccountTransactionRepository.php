<?php
declare(strict_types=1);

namespace Demo\Repository;

use DateTime;
use Demo\Models\AccountTransactions;
use Demo\Repository\Api\AccountTransactionRepositoryInterface;
use Demo\Repository\Api\UserAccountRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AccountTransactionRepository implements AccountTransactionRepositoryInterface
{

    /**
     * @var AccountTransactions
     */
    private $accountTransactions;

    /**
     * @var UserAccountRepositoryInterface
     */
    private $userAccountRepository;

    /**
     * @var UsersRepository
     */
    private $userRepository;

    /**
     * AccountTransactionRepository constructor.
     * @param AccountTransactions $accountTransactions
     * @param UserAccountRepositoryInterface $userAccountRepository
     * @param UsersRepository $userRepository
     */
    public function __construct(AccountTransactions $accountTransactions,
                                UserAccountRepositoryInterface $userAccountRepository,
                                UsersRepository $userRepository)
    {
        $this->accountTransactions = $accountTransactions;
        $this->userAccountRepository = $userAccountRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param $parameter
     * @param $data
     * @return mixed
     */
    public function whereFirst($parameter, $data)
    {
        return $this->accountTransactions->where($parameter,$data)->first();
    }

    /**
     * @param $user
     * @return mixed
     */
    public function create($user)
    {
        return $this->accountTransactions->create($user);
    }

    /**
     * @return AccountTransactions[]|Collection
     */
    public function list()
    {
        return $this->accountTransactions->all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->accountTransactions->find($id);
    }

    /**
     * @param $id
     * @param $update
     * @return mixed
     */
    public function update($id, $update)
    {
        return $this->accountTransactions->where('id',$id)->update($update);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function remove($id)
    {
        return $this->accountTransactions->find($id)->delete();
    }

    /**
     * @param $request
     * @return array
     */
    public function defineTransaction($request): ?array
    {
        $transaction = [];
        $user = getUser();

        date_default_timezone_set("America/Sao_Paulo");
        $date = date("d/m/Y H:i:s");

        switch ($request["type"]){
            case 1:
                $transaction = ['account_origin_id' => $user->account->id,
                    'account_destination_id' => $user->account->id,
                    'value' => xorEncrypt($request["value"]),
                    'transaction_type' => xorEncrypt('1'),
                    'transaction_date' => xorEncrypt($date)];
                break;
            case 2:
                $destination_account = $this->userAccountRepository->whereFirst('account_number',xorEncrypt($request['destination_account']));
                $transaction = ['account_origin_id' => $user->account->id,
                    'account_destination_id' => $destination_account->id,
                    'value' => xorEncrypt($request["value"]),
                    'transaction_type' => xorEncrypt('2'),
                    'transaction_date' => xorEncrypt($date)];
                break;
            case 3:
                $transaction = ['account_origin_id' => $user->account->id,
                    'account_destination_id' => $user->account->id,
                    'value' => xorEncrypt($request["value"]),
                    'transaction_type' => xorEncrypt('3'),
                    'transaction_date' => xorEncrypt($date)];
                break;
        }

        return $transaction;
    }

    /**
     * @param $transaction
     * @return false
     */
    public function executeTransaction($transaction)
    {
        $result = false;
        switch( xorEncrypt($transaction["transaction_type"], "decrypt")){
            case 1:
                $result = $this->userAccountRepository->deposit($transaction);
                break;
            case 2:
                $result = $this->userAccountRepository->transfer($transaction);
                break;
            case 3:
                $result = $this->userAccountRepository->withdraw($transaction);
                break;
        }

        return $result;
    }

    /**
     * @return AccountTransactions[]|Collection
     */
    public function listById($id)
    {
        $userAccount = $this->userAccountRepository->whereFirst('user_id',$id);
        return $this->accountTransactions->where('account_origin_id',$userAccount->id)->get();
    }

    /**
     *
     */
    public function getListByUserIdDecrypted()
    {
        $user = getUser();

        $transactionList = $this->accountTransactions->where('account_origin_id',$user->account->id)->orWhere('account_destination_id',$user->account->id)->get();
        $transactionList->load('destinationAccount','originAccount');
        $decrypt = ['value',
                    'transaction_type',
                    'transaction_date'];
        $transaction = [];
        foreach($transactionList as $key => $transactionK){
            $transactionListAux = ['account_origin_id' => xorEncrypt($transactionK->originAccount->account_number, 'decrypt'),
                                   'account_destination_id' => xorEncrypt($transactionK->destinationAccount->account_number,'decrypt')];
            $transactionMerge = [$transactionK->id => array_merge($transactionListAux, helperDecryptArray($transactionK->getAttributes(), $decrypt)) ];
            $transaction = array_merge($transaction , $transactionMerge);
        }

        return ['transaction' => $transaction];
    }
}
