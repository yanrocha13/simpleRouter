<?php
declare(strict_types=1);

namespace Demo\Repository;

use Demo\Models\UserPhone;
use Demo\Repository\Api\UserPhoneRepositoryInterface;

class UserPhoneRepository implements UserPhoneRepositoryInterface
{

    /**
     * @var UserPhone
     */
    private $userPhone;

    /**
     * AccountTransactionRepository constructor.
     * @param UserPhone $userPhone
     */
    public function __construct(UserPhone $userPhone)
    {
        $this->userPhone = $userPhone;
    }

    public function whereFirst($parameter, $data){
        return $this->userPhone->where($parameter,$data)->first();
    }

    public function create($userPhone){
        return $this->userPhone->create($userPhone);
    }

    public function list(){
        return $this->userPhone->all();
    }

    public function find($id){
        return $this->userPhone->find($id);
    }

    public function update($id, $update){
        return $this->userPhone->where('id',$id)->update($update);
    }

    public function remove($id){
        return $this->userPhone->find($id)->delete();
    }
}
