<?php
declare(strict_types=1);

namespace Demo\Repository;

use Demo\Models\Users;
use Demo\Repository\Api\UserAccountRepositoryInterface;
use Demo\Repository\Api\UserAddressRepositoryInterface;
use Demo\Repository\Api\UserPhoneRepositoryInterface;
use Demo\Repository\Api\UserRepositoryInterface;

class UsersRepository implements UserRepositoryInterface
{

    /**
     * @var Users
     */
    private $users;

    /**
     * @var UserPhoneRepositoryInterface
     */
    private $userPhoneRepository;

    /**
     * @var UserAddressRepositoryInterface
     */
    private $userAddressRepository;

    /**
     * @var UserAccountRepositoryInterface
     */
    private $userAccountRepository;

    /**
     * UsersRepository constructor.
     * @param Users $users
     * @param UserPhoneRepositoryInterface $userPhoneRepository
     * @param UserAddressRepositoryInterface $userAddressRepository
     * @param UserAccountRepositoryInterface $userAccountRepository
     */
    public function __construct(Users $users,
                                UserPhoneRepositoryInterface $userPhoneRepository,
                                UserAddressRepositoryInterface $userAddressRepository,
                                UserAccountRepositoryInterface $userAccountRepository)
    {
        $this->users = $users;
        $this->userPhoneRepository = $userPhoneRepository;
        $this->userAddressRepository = $userAddressRepository;
        $this->userAccountRepository = $userAccountRepository;
    }

    /**
     * @param $parameter
     * @param $data
     * @return mixed
     */
    public function whereFirst($parameter, $data){
        return $this->users->where($parameter,$data)->first();
    }

    /**
     * @param $parameter
     * @param $data
     * @param null $relation
     * @return mixed
     */
    public function whereFirstWithRelation($parameter, $data, $relation = null){
        return $this->users->where($parameter,$data)->with($relation)->first();
    }

    /**
     * @param $user
     * @return mixed
     */
    public function create($user){
        return $this->users->create($user);
    }

    /**
     * @return Users[]|\Illuminate\Database\Eloquent\Collection
     */
    public function list(){
        return $this->users->all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id){
        return $this->users->find($id);
    }

    /**
     * @param $id
     * @param $update
     * @return mixed
     */
    public function update($id, $update){
        return $this->users->where('id',$id)->update($update);
    }

    /**
     * @param $id
     * @return null
     */
    public function remove($id){
        return null;
    }

    /**
     * @param $parameter
     * @param $data
     * @return array
     */
    public function getFirstUserDecrypted($parameter, $data){
        $user = $this->whereFirst('id',5);

        $decrypt = ['email',
            'name',
            'identification',
            'registration',
            'birth_date'];

        return helperDecryptArray($user->getAttributes(), $decrypt);
    }
}
