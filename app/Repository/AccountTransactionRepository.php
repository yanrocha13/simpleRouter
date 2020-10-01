<?php
declare(strict_types=1);

namespace Demo\Repository;

use Demo\Models\AccountTransactions;
use Demo\Models\UserAccount;
use Demo\Models\Users;
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
    public function whereFirst($parameter, $data){
        return $this->accountTransactions->where($parameter,$data)->first();
    }

    /**
     * @param $user
     * @return mixed
     */
    public function create($user){
        return $this->accountTransactions->create($user);
    }

    /**
     * @return AccountTransactions[]|Collection
     */
    public function list(){
        return $this->accountTransactions->all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id){
        return $this->accountTransactions->find($id);
    }

    /**
     * @param $id
     * @param $update
     * @return mixed
     */
    public function update($id, $update){
        return $this->accountTransactions->where('id',$id)->update($update);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function remove($id){
        return $this->accountTransactions->find($id)->delete();
    }

    /**
     * @param $request
     * @return array
     */
    public function defineTransaction($request){
        $transaction = [];
        //TODO MODIFY ACCOUNT ORIGIN/DESTINATION TO CATCH WITH LOCAL USER WHEN TYPE 1/3 WHEN TYPE EQUAL 2 CATCH ACCOUNT NUMBER FROM REQUEST

        $user = $this->userRepository->whereFirstWithRelation('email',xorEncrypt($request['user_email']),['account']);
        $date = new DateTime();

        switch ($request["type"]){
            case 1:
                $transaction = ['account_origin_id' => $user->account->id,
                    'account_destination_id' => $user->account->id,
                    'value' => xorEncrypt($request["value"]),
                    'transaction_type' => xorEncrypt('1'),
                    'transaction_date' => xorEncrypt($date)];
                break;
            case 2:
                $destination_account = $this->userAccountRepository->whereFirst('user_account',$request['destination_account']);
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

    public function executeTransaction($transaction){
        $result = false;
        switch( $transaction["type"]){
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
    public function listById($id){
        $userAccount = $this->userAccountRepository->whereFirst('user_id',$id);
        return $this->accountTransactions->where('account_origin_id',$userAccount->id)->get();
    }
}
