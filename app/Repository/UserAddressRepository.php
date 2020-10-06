<?php
declare(strict_types=1);

namespace Demo\Repository;

use Demo\Models\UserAddress;
use Demo\Repository\Api\UserAddressRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * @param $parameter
     * @param $data
     * @return mixed
     */
    public function whereFirst($parameter, $data){
        return $this->userAddress->where($parameter,$data)->first();
    }

    /**
     * @param $user
     * @return mixed
     */
    public function create($user){
        return $this->userAddress->create($user);
    }

    /**
     * @return UserAddress[]|Collection
     */
    public function list(){
        return $this->userAddress->all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id){
        return $this->userAddress->find($id);
    }

    /**
     * @param $id
     * @param $update
     * @return mixed
     */
    public function update($id, $update){
        return $this->userAddress->where('id',$id)->update($update);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function remove($id){
        return $this->userAddress->find($id)->delete();
    }

    /**
     * @param $parameter
     * @param $data
     * @return array[]
     */
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
