<?php
declare(strict_types=1);

namespace Demo\Repository;

use Demo\Models\UserAddress;
use Demo\Repository\Api\UserAddressRepositoryInterface;

class UserAddressRepository implements UserAddressRepositoryInterface
{

    /**
     * @var UserAddress
     */
    private $userAddress;

    /**
     * UsersRepository constructor.
     * @param UserAddress $userAddress
     */
    public function __construct(UserAddress $userAddress)
    {
        $this->userAddress = $userAddress;
    }

    public function whereFirst($parameter, $data){
        return $this->userAddress->where($parameter,$data)->first();
    }

    public function create($user){
        return $this->userAddress->create($user);
    }

    public function list(){
        return $this->userAddress->all();
    }

    public function find($id){
        return $this->userAddress->find($id);
    }

    public function update($id, $update){
        return $this->userAddress->where('id',$id)->update($update);
    }

    public function remove($id){
        return $this->userAddress->find($id)->delete();
    }

    public function getAddressListDecrypted($parameter, $data)
    {
        $addressList = $this->userAddress->where($parameter,$data)->get();
        $decrypt = ['CEP',
                    'address',
                    'number',
                    'reference',
                    'observation'];
        $address = [];
        foreach($addressList as $key => $addressi){
            $address = array_merge($address , [$addressi->id => helperDecryptArray($addressi->getAttributes(), $decrypt)]);
        }

        return ['address' => $address];
    }
}
