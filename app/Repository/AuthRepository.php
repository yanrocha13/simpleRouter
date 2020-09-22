<?php
declare(strict_types=1);

namespace Demo\Repository;

use Demo\Models\Users;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;

class AuthRepository extends Model
{

    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var Sha256
     */
    private $sha256;

    /**
     * @var UsersRepository
     */
    private $userRepository;

    /**
     * Auth constructor.
     * @param Builder $builder
     * @param Sha256 $sha256
     */
    public function __construct(Builder $builder, Sha256 $sha256, UsersRepository $userRepository)
    {
       $this->builder = $builder;
       $this->sha256 = $sha256;
       $this->userRepository = $userRepository;
    }


    /**
     * @param $user
     * @return mixed|string
     */
    public function authentication($user)
    {
        try {
            $users = $this->userRepository->whereFirst('email',xorEncrypt($user['email']));

            if(isset($users) && $users->password == xorEncrypt($user['password'])){
                $token = $this->makeToken($users);
                setcookie("authentication", $token, time()+3600);
                return $token;
            }
            else{
               return 'oi';
            }
        }
        catch(Exception $ex)
        {
            return 'doi';
        }
    }

    /**
     * @param Users $users
     * @return mixed
     */
    public function makeToken(Users $users)
    {
        $token = xorEncrypt($users->email . ':' . $users->password);
        return $token;
    }


}
