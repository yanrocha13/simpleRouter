<?php
declare(strict_types=1);

namespace Demo\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;

class Auth extends Model
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
     * Auth constructor.
     * @param Builder $builder
     * @param Sha256 $sha256
     */
    public function __construct(Builder $builder, Sha256 $sha256)
    {
       $this->builder = $builder;
       $this->sha256 = $sha256;
    }


    /**
     * @param $user
     * @return mixed|string
     */
    public function authentication($user)
    {
        try {
            $users = Users::where('email',xorEncrypt($user['email']))->first();

            if(isset($users) && $users->password == xorEncrypt($user['password'])){
                $token = $this->makeJWT($users);
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
    public function makeJWT(Users $users)
    {
/*        $time = time();

        $token = (new Builder)->issuedBy('$users->email')
            ->permittedFor('')
            ->identifiedBy('4f1g23aa12aa',true)
            ->issuedAt($time)
            ->canOnlyBeUsedAfter($time + 60)
            ->expiresAt($time + 3600)
            ->withClaim('uid', 1)
            ->getToken($this->sha256, new Key('teste'));*/

        $token = xorEncrypt($users->email . ':' . $users->password);

        return $token;
    }


}
