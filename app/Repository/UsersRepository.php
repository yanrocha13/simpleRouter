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

    public function whereFirst($parameter, $data){
        return $this->users->where($parameter,$data)->first();
    }

    public function create($user){
        return $this->users->create($user);
    }

    public function list(){
        return $this->users->all();
    }

    public function find($id){
        return $this->users->find($id);
    }

    public function update($id, $update){
        return $this->users->where('id',$id)->update($update);
    }

    public function remove($id){
        return null;
    }
}
