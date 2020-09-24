<?php
declare(strict_types=1);

namespace Demo\Repository;

use Demo\Models\AccountTransactions;
use Demo\Repository\Api\AccountTransactionRepositoryInterface;

class AccountTransactionRepository implements AccountTransactionRepositoryInterface
{

    /**
     * @var AccountTransactions
     */
    private $accountTransactions;

    /**
     * AccountTransactionRepository constructor.
     * @param AccountTransactions $accountTransactions
     */
    public function __construct(AccountTransactions $accountTransactions)
    {
        $this->accountTransactions = $accountTransactions;
    }

    public function whereFirst($parameter, $data){
        return $this->accountTransactions->where($parameter,$data)->first();
    }

    public function create($user){
        return $this->accountTransactions->create($user);
    }

    public function list(){
        return $this->accountTransactions->all();
    }

    public function find($id){
        return $this->accountTransactions->find($id);
    }

    public function update($id, $update){
        return $this->accountTransactions->where('id',$id)->update($update);
    }

    public function remove($id){
        return $this->accountTransactions->find($id)->delete();
    }

}
