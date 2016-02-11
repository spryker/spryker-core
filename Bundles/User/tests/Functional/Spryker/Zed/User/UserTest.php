<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\User;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\User\Business\UserFacade;

/**
 * @group Spryker
 * @group Zed
 * @group User
 */
class UserTest extends Test
{

    /**
     * @var \Spryker\Zed\User\Business\UserFacade
     */
    protected $userFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->userFacade = new UserFacade();
    }

    /**
     * @return array
     */
    private function mockUserData()
    {
        $data['firstName'] = sprintf('Test-%s', rand(100, 999));
        $data['lastName'] = sprintf('LastName-%s', rand(100, 999));
        $data['username'] = sprintf('Username-%s', rand(100, 999));
        $data['password'] = sprintf('Password-%s', rand(100, 999));

        return $data;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    private function mockAddUser($data)
    {
        return $this->userFacade->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    private function mockUserTransfer($data)
    {
        $dto = new UserTransfer();

        $dto->setFirstName($data['firstName']);
        $dto->setLastName($data['lastName']);
        $dto->setUsername($data['username']);
        $dto->setPassword($data['password']);

        return $dto;
    }

    /**
     * @return void
     */
    public function testAddUser()
    {
        $data = $this->mockUserData();

        $user = $this->userFacade->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserTransfer', $user);
        $this->assertNotNull($user->getIdUser());
        $this->assertEquals($data['firstName'], $user->getFirstName());
        $this->assertEquals($data['lastName'], $user->getLastName());
        $this->assertEquals($data['username'], $user->getUsername());
        $this->assertNotEquals($data['password'], $user->getPassword());
    }

    /**
     * @return void
     */
    public function testAfterCallToRemoveUserGetUserByIdMustThrowAnExcetpion()
    {
        $data = $this->mockUserData();
        $user = $this->userFacade->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserTransfer', $user);

        $this->userFacade->removeUser($user->getIdUser());

        $this->setExpectedException('\Spryker\Zed\User\Business\Exception\UserNotFoundException');
        $this->userFacade->getActiveUserById($user->getIdUser());
    }

    /**
     * @return void
     */
    public function testUpdateUserWithSamePassword()
    {
        $data = $this->mockUserData();
        $data2 = $this->mockUserData();

        $user = $this->userFacade->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);

        $user->setFirstName($data2['firstName']);
        $user->setLastName($data2['lastName']);
        $user->setUsername($data2['username']);
        $user->setPassword($data['password']);

        $userTest = clone $user;
        $finalUser = $this->userFacade->updateUser($userTest);

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserTransfer', $finalUser);
        $this->assertEquals($user->getFirstName(), $finalUser->getFirstName());
        $this->assertEquals($user->getLastName(), $finalUser->getLastName());
        $this->assertEquals($user->getUsername(), $finalUser->getUsername());
        $this->assertNotEquals($user->getPassword(), $finalUser->getPassword());

        $this->assertTrue($this->userFacade->isValidPassword($data['password'], $finalUser->getPassword()));
    }

    /**
     * @return void
     */
    public function testUpdateUserWithNewPassword()
    {
        $data = $this->mockUserData();
        $data2 = $this->mockUserData();

        $user = $this->userFacade->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);

        $user->setFirstName($data2['firstName']);
        $user->setLastName($data2['lastName']);
        $user->setUsername($data2['username']);
        $user->setPassword($data2['password']);

        $userTest = clone $user;
        $finalUser = $this->userFacade->updateUser($userTest);

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserTransfer', $finalUser);
        $this->assertEquals($user->getFirstName(), $finalUser->getFirstName());
        $this->assertEquals($user->getLastName(), $finalUser->getLastName());
        $this->assertEquals($user->getUsername(), $finalUser->getUsername());
        $this->assertNotEquals($user->getPassword(), $finalUser->getPassword());

        $this->assertTrue($this->userFacade->isValidPassword($data2['password'], $finalUser->getPassword()));
    }

    /**
     * @return void
     */
    public function testGetUserByUsername()
    {
        $data = $this->mockUserData();
        $mock = $this->mockAddUser($data);

        $user = $this->userFacade->getUserByUsername($data['username']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserTransfer', $user);
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

        $user = $this->userFacade->getUserById($mock->getIdUser());

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserTransfer', $user);
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

        $this->assertTrue($this->userFacade->isValidPassword($data['password'], $user->getPassword()));
    }

}
