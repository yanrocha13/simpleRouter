<?php
declare(strict_types=1);


use Demo\Models\Users;
use Demo\Repository\Api\UserAccountRepositoryInterface;
use Demo\Repository\Api\UserAddressRepositoryInterface;
use Demo\Repository\Api\UserPhoneRepositoryInterface;
use Demo\Repository\Api\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;

class UsersRepositoryTest extends TestCase
{
    /**
     * @var Users
     */
    private $users;

    /**
     * @var Collection
     */
    private $userCollection;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

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
     *
     */
    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->userPhoneRepository = $this->createMock(UserPhoneRepositoryInterface::class);
        $this->userAddressRepository = $this->createMock(UserAddressRepositoryInterface::class);
        $this->userAccountRepository = $this->createMock(UserAccountRepositoryInterface::class);
        $this->userCollection = $this->createMock(Collection::class);
        $this->users = $this->createPartialMock(Users::class, [
            'where',
            'first',
            'create',
            'all',
            'find'
        ]);
    }

    public function testWhereFirstShouldReturnUser(): void
    {
        $this->users->expects($this->once())
            ->method('where')
            ->willReturn($this->users);

        $this->users->expects($this->once())
            ->method('first')
            ->willReturn($this->users);

        $result = $this->userRepository->whereFirst('id','5');
        $this->assertEquals($this->users,$result);

    }
}
