<?php
namespace Functional\SprykerFeature\Zed\User;

use Codeception\TestCase\Test;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\User\Business\Exception\UserNotFoundException;
use SprykerFeature\Zed\User\Business\UserFacade;
use SprykerEngine\Zed\Kernel\Business\Factory;

/**
 * @group UserTest
 */
class UserTest extends Test
{
    /**
     * @var UserFacade $userFacade
     */
    private $userFacade;

    /**
     * @var AutoCompletion
     */
    private $locator;

    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();

        $this->userFacade = new UserFacade(
            new Factory('User'),
            $this->locator
        );
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
     * @param $data
     * @return NULL|\SprykerFeature\Shared\User\Transfer\User
     */
    private function mockAddUser($data)
    {
        return $this->userFacade->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);
    }

    /**
     * @param $data
     * @return \SprykerFeature\Shared\User\Transfer\User
     */
    private function mockUserDto($data)
    {
        $dto = new \Generated\Shared\Transfer\UserUserTransfer();

        $dto->setFirstName($data['firstName']);
        $dto->setLastName($data['lastName']);
        $dto->setUsername($data['username']);
        $dto->setPassword($data['password']);

        return $dto;
    }

    /**
     * @group User
     */
    public function testAddUser()
    {
        $data = $this->mockUserData();

        $user = $this->userFacade->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserUser', $user)Transfer;
        $this->assertNotNull($user->getIdUserUser());
        $this->assertEquals($data['firstName'], $user->getFirstName());
        $this->assertEquals($data['lastName'], $user->getLastName());
        $this->assertEquals($data['username'], $user->getUsername());
        $this->assertNotEquals($data['password'], $user->getPassword());
    }

    /**
     * @group User
     */
    public function testRemoveUser()
    {
        $data = $this->mockUserData();

        $user = $this->userFacade->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserUser', $user)Transfer;
        $this->assertNotNull($user->getIdUserUser());
        $this->assertEquals($data['firstName'], $user->getFirstName());
        $this->assertEquals($data['lastName'], $user->getLastName());
        $this->assertEquals($data['username'], $user->getUsername());
        $this->assertNotEquals($data['password'], $user->getPassword());

        $this->userFacade->removeUser($user->getIdUserUser());

        try {
            $this->userFacade->getUserById($user->getIdUserUser());
        } catch (UserNotFoundException $e) {
            $this->assertInstanceOf('\SprykerFeature\Zed\User\Business\Exception\UserNotFoundException', $e);
        }
    }

    /**
     * @group User
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

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserUser', $finalUser)Transfer;
        $this->assertEquals($user->getFirstName(), $finalUser->getFirstName());
        $this->assertEquals($user->getLastName(), $finalUser->getLastName());
        $this->assertEquals($user->getUsername(), $finalUser->getUsername());
        $this->assertNotEquals($user->getPassword(), $finalUser->getPassword());

        $this->assertTrue($this->userFacade->isValidPassword($data['password'], $finalUser->getPassword()));
    }

    /**
     * @group User
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

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserUser', $finalUser)Transfer;
        $this->assertEquals($user->getFirstName(), $finalUser->getFirstName());
        $this->assertEquals($user->getLastName(), $finalUser->getLastName());
        $this->assertEquals($user->getUsername(), $finalUser->getUsername());
        $this->assertNotEquals($user->getPassword(), $finalUser->getPassword());

        $this->assertTrue($this->userFacade->isValidPassword($data2['password'], $finalUser->getPassword()));
    }

    /**
     * @group User
     */
    public function testGetUserByUsername()
    {
        $data = $this->mockUserData();
        $mock = $this->mockAddUser($data);

        $user = $this->userFacade->getUserByUsername($data['username']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserUser', $user)Transfer;
        $this->assertEquals($user->getIdUserUser(), $mock->getIdUserUser());
        $this->assertEquals($user->getFirstName(), $mock->getFirstName());
        $this->assertEquals($user->getLastName(), $mock->getLastName());
        $this->assertEquals($user->getUsername(), $mock->getUsername());
        $this->assertEquals($user->getPassword(), $mock->getPassword());
    }

    /**
     * @group User
     */
    public function testGetUserById()
    {
        $data = $this->mockUserData();
        $mock = $this->mockAddUser($data);

        $user = $this->userFacade->getUserById($mock->getIdUserUser());

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserUser', $user)Transfer;
        $this->assertEquals($user->getIdUserUser(), $mock->getIdUserUser());
        $this->assertEquals($user->getFirstName(), $mock->getFirstName());
        $this->assertEquals($user->getLastName(), $mock->getLastName());
        $this->assertEquals($user->getUsername(), $mock->getUsername());
        $this->assertEquals($user->getPassword(), $mock->getPassword());
    }

    /**
     * @group User
     */
    public function testIsValidPassword()
    {
        $data = $this->mockUserData();
        $user = $this->mockAddUser($data);

        $this->assertTrue($this->userFacade->isValidPassword($data['password'], $user->getPassword()));
    }
}
