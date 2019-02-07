<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\UserBuilder;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\Session\SessionClient;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;
use Spryker\Zed\User\Business\Model\User;
use Spryker\Zed\User\Persistence\UserQueryContainerInterface;
use Spryker\Zed\User\UserConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group User
 * @group Business
 * @group UserTest
 * Add your own group annotations below this line
 */
class UserTest extends Unit
{
    /**
     * @const string
     */
    public const USERNAME = 'test@test.com';

    /**
     * @var \SprykerTest\Zed\User\BusinessTester
     */
    public $tester;

    /**
     * @return \Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected function getUserFacade()
    {
        return $this->tester->getLocator()->user()->facade();
    }

    /**
     * @return array
     */
    private function mockUserData()
    {
        $data = [];

        $data['firstName'] = sprintf('Test-%s', rand(100, 999));
        $data['lastName'] = sprintf('LastName-%s', rand(100, 999));
        $data['username'] = sprintf('Username-%s', rand(100, 999));
        $data['password'] = sprintf('Password-%s', rand(100, 999));

        return $data;
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function getUserDataTransfer(): UserTransfer
    {
        return (new UserBuilder())->build();
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    private function mockAddUser($data)
    {
        return $this->getUserFacade()->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);
    }

    /**
     * @return void
     */
    public function testAddUser()
    {
        $data = $this->mockUserData();

        $user = $this->getUserFacade()->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);

        $this->assertInstanceOf(UserTransfer::class, $user);
        $this->assertNotNull($user->getIdUser());
        $this->assertEquals($data['firstName'], $user->getFirstName());
        $this->assertEquals($data['lastName'], $user->getLastName());
        $this->assertEquals($data['username'], $user->getUsername());
        $this->assertNotEquals($data['password'], $user->getPassword());
    }

    /**
     * @return void
     */
    public function testCreateUser()
    {
        $data = $this->getUserDataTransfer();

        $user = $this->getUserFacade()->createUser($data);

        $this->assertInstanceOf(UserTransfer::class, $user);
        $this->assertNotNull($user->getIdUser());
        $this->assertEquals($data->getFirstName(), $user->getFirstName());
        $this->assertEquals($data->getLastName(), $user->getLastName());
        $this->assertEquals($data->getUsername(), $user->getUsername());
        $this->assertNotEquals($data->getPassword(), $user->getPassword());
    }

    /**
     * @return void
     */
    public function testAfterCallToRemoveUserGetUserByIdMustThrowAnException()
    {
        $data = $this->mockUserData();
        $user = $this->getUserFacade()->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);

        $this->assertInstanceOf(UserTransfer::class, $user);

        $this->getUserFacade()->removeUser($user->getIdUser());

        $this->expectException(UserNotFoundException::class);
        $this->getUserFacade()->getActiveUserById($user->getIdUser());
    }

    /**
     * @return void
     */
    public function testUpdateUserWithSamePassword()
    {
        $data = $this->mockUserData();
        $data2 = $this->mockUserData();

        $user = $this->getUserFacade()->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);

        $user2 = clone $user;
        $user2->setFirstName($data2['firstName']);
        $user2->setLastName($data2['lastName']);
        $user2->setUsername($data2['username']);
        $user2->setPassword($data['password']);
        $user2 = $this->getUserFacade()->updateUser($user2);

        $this->assertInstanceOf(UserTransfer::class, $user2);
        $this->assertEquals($data2['firstName'], $user2->getFirstName());
        $this->assertEquals($data2['lastName'], $user2->getLastName());
        $this->assertEquals($data2['username'], $user2->getUsername());
        $this->assertNotEquals($user->getPassword(), $user2->getPassword());

        $this->assertTrue($this->getUserFacade()->isValidPassword($data['password'], $user2->getPassword()));
    }

    /**
     * When hash is present in the user it should not be rehashed.
     *
     * @return void
     */
    public function testUpdateUserWithSamePasswordHash()
    {
        $data = $this->mockUserData();
        $user = $this->getUserFacade()->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);

        $user2 = clone $user;
        $user2 = $this->getUserFacade()->updateUser($user2);

        $hashedPassword = $user->getPassword();
        $newHashedPassword = $user2->getPassword();
        $this->assertEquals($hashedPassword, $newHashedPassword);

        $user2 = $this->getUserFacade()->updateUser($user2);
        $newHashedPassword = $user2->getPassword();
        $this->assertEquals($hashedPassword, $newHashedPassword);
    }

    /**
     * @return void
     */
    public function testUpdateUserWithNewPassword()
    {
        $data = $this->mockUserData();
        $data2 = $this->mockUserData();

        $user = $this->getUserFacade()->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);

        $user->setFirstName($data2['firstName']);
        $user->setLastName($data2['lastName']);
        $user->setUsername($data2['username']);
        $user->setPassword($data2['password']);

        $userTest = clone $user;
        $finalUser = $this->getUserFacade()->updateUser($userTest);

        $this->assertInstanceOf(UserTransfer::class, $finalUser);
        $this->assertEquals($user->getFirstName(), $finalUser->getFirstName());
        $this->assertEquals($user->getLastName(), $finalUser->getLastName());
        $this->assertEquals($user->getUsername(), $finalUser->getUsername());
        $this->assertNotEquals($user->getPassword(), $finalUser->getPassword());

        $this->assertTrue($this->getUserFacade()->isValidPassword($data2['password'], $finalUser->getPassword()));
    }

    /**
     * @return void
     */
    public function testUpdateWithPasswordHashIgnored()
    {
        $data = $this->mockUserData();
        $data2 = $this->mockUserData();

        $user = $this->getUserFacade()->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);

        $user2 = clone $user;
        $user2->setPassword($data2['password']);
        $user2 = $this->getUserFacade()->updateUser($user2);

        $this->assertNotEquals($user->getPassword(), $user2->getPassword());
        $this->assertTrue($this->getUserFacade()->isValidPassword($data2['password'], $user2->getPassword()));

        $user3 = clone $user2;
        $user3->setPassword($user->getPassword());
        $user3 = $this->getUserFacade()->updateUser($user3);

        $this->assertEquals($user3->getPassword(), $user2->getPassword());
        $this->assertNotEquals($user3->getPassword(), $user->getPassword());
        $this->assertNotEquals($user3->getPassword(), $data2['password']);
        $this->assertNotEquals($user3->getPassword(), $data['password']);
    }

    /**
     * @return void
     */
    public function testGetUserByUsername()
    {
        $data = $this->mockUserData();
        $mock = $this->mockAddUser($data);

        $user = $this->getUserFacade()->getUserByUsername($data['username']);

        $this->assertInstanceOf(UserTransfer::class, $user);
        $this->assertEquals($user->getIdUser(), $mock->getIdUser());
        $this->assertEquals($user->getFirstName(), $mock->getFirstName());
        $this->assertEquals($user->getLastName(), $mock->getLastName());
        $this->assertEquals($user->getUsername(), $mock->getUsername());
        $this->assertEquals($user->getPassword(), $mock->getPassword());
    }

    /**
     * @return void
     */
    public function testGetUserById()
    {
        $data = $this->mockUserData();
        $mock = $this->mockAddUser($data);

        $user = $this->getUserFacade()->getUserById($mock->getIdUser());

        $this->assertInstanceOf(UserTransfer::class, $user);
        $this->assertEquals($user->getIdUser(), $mock->getIdUser());
        $this->assertEquals($user->getFirstName(), $mock->getFirstName());
        $this->assertEquals($user->getLastName(), $mock->getLastName());
        $this->assertEquals($user->getUsername(), $mock->getUsername());
        $this->assertEquals($user->getPassword(), $mock->getPassword());
    }

    /**
     * @return void
     */
    public function testIsValidPassword()
    {
        $data = $this->mockUserData();
        $user = $this->mockAddUser($data);

        $this->assertTrue($this->getUserFacade()->isValidPassword($data['password'], $user->getPassword()));
    }

    /**
     * @return void
     */
    public function testUserTransferClonedBeforeStoringInSession()
    {
        $sessionClient = $this->createSessionClient();

        $userModel = new User(
            $this->createQueryContainer(),
            $sessionClient,
            new UserConfig()
        );

        $userTransfer = $this->createUserTransfer(static::USERNAME);

        // Checks that User TO is cloned before being saved into session.
        $sessionClient->expects($this->once())
            ->method('set')
            ->with(
                $this->stringContains('user'),
                $this->logicalAnd(
                    $this->equalTo($userTransfer),
                    $this->logicalNot($this->identicalTo($userTransfer))
                )
            );

        $userModel->setCurrentUser($userTransfer);
    }

    /**
     * @return void
     */
    public function testUserTransferClonedAfterReadingFromSession()
    {
        $sessionClient = $this->createSessionClient();

        $userModel = new User(
            $this->createQueryContainer(),
            $sessionClient,
            new UserConfig()
        );

        $userTransfer = $this->createUserTransfer(static::USERNAME);

        // Checks that User TO is cloned after reading from session and before returning to caller.
        $sessionClient->expects($this->once())
            ->method('get')
            ->will($this->returnValue($userTransfer));

        $sessionClient->expects($this->once())
            ->method('has')
            ->will($this->returnValue(true));

        $userFromSession = $userModel->getCurrentUser();
        $this->assertEquals($userTransfer, $userFromSession);
        $this->assertNotSame($userTransfer, $userFromSession);
    }

    /**
     * @return void
     */
    public function testHasCurrentUserReturnsFalseOnNull()
    {
        $sessionClient = $this->createSessionClient();

        $userModel = new User(
            $this->createQueryContainer(),
            $sessionClient,
            new UserConfig()
        );

        $sessionClient->expects($this->once())
            ->method('has')
            ->will($this->returnValue(null));

        $hasCurrentUser = $userModel->hasCurrentUser();
        $this->assertFalse($hasCurrentUser);
    }

    /**
     * @param string $userName
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function createUserTransfer($userName)
    {
        $userTransfer = new UserTransfer();
        $userTransfer
            ->setPassword('test')
            ->setIdUser(1)
            ->setFirstName('test')
            ->setLastName('test')
            ->setLastLogin('test')
            ->setUsername($userName);

        return $userTransfer;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Session\SessionClient
     */
    protected function createSessionClient()
    {
        $sessionClient = $this->getMockBuilder(SessionClient::class)->setMethods(['get', 'set', 'has'])->getMock();

        return $sessionClient;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\User\Persistence\UserQueryContainerInterface
     */
    protected function createQueryContainer()
    {
        $queryContainer = $this->getMockBuilder(UserQueryContainerInterface::class)->getMock();

        return $queryContainer;
    }
}
