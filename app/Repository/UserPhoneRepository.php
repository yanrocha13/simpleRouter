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

    /**
     * @param $parameter
     * @param $data
     * @return mixed
     */
    public function whereFirst($parameter, $data){
        return $this->userPhone->where($parameter,$data)->first();
    }

    /**
     * @param $userPhone
     * @return mixed
     */
    public function create($userPhone){
        return $this->userPhone->create($userPhone);
    }

    /**
     * @return UserPhone[]|\Illuminate\Database\Eloquent\Collection
     */
    public function list(){
        return $this->userPhone->all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id){
        return $this->userPhone->find($id);
    }

    /**
     * @param $id
     * @param $update
     * @return mixed
     */
    public function update($id, $update){
        return $this->userPhone->where('id',$id)->update($update);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function remove($id){
        return $this->userPhone->find($id)->delete();
    }

    /**
     * @param $parameter
     * @param $data
     * @return array[]
     */
    public function getPhoneListDecrypted($parameter, $data)
    {
        $phoneList = $this->userPhone->where($parameter,$data)->get();
        $decrypt = ['phone'];
        $phone = [];
        foreach($phoneList as $key => $phonei){
            $phone = array_merge($phone , [$phonei->id => helperDecryptArray($phonei->getAttributes(), $decrypt)]);
        }

        return ['phone' => $phone];
    }
}
