<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MailBuilder;
use Generated\Shared\DataBuilder\UserBuilder;
use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\Session\SessionClient;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;
use Spryker\Zed\User\Business\Model\User;
use Spryker\Zed\User\Business\UserFacadeInterface;
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
     * @var \SprykerTest\Zed\User\UserBusinessTester
     */
    public $tester;

    /**
     * @return \Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected function getUserFacade(): UserFacadeInterface
    {
        return $this->tester->getLocator()->user()->facade();
    }

    /**
     * @return array
     */
    private function mockUserData(): array
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
    private function mockAddUser(array $data): UserTransfer
    {
        return $this->getUserFacade()->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);
    }

    /**
     * @return void
     */
    public function testAddUser(): void
    {
        $data = $this->mockUserData();

        $user = $this->getUserFacade()->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);

        $this->assertInstanceOf(UserTransfer::class, $user);
        $this->assertNotNull($user->getIdUser());
        $this->assertSame($data['firstName'], $user->getFirstName());
        $this->assertSame($data['lastName'], $user->getLastName());
        $this->assertSame($data['username'], $user->getUsername());
        $this->assertNotEquals($data['password'], $user->getPassword());
    }

    /**
     * @return void
     */
    public function testCreateUser(): void
    {
        $data = $this->getUserDataTransfer();

        $user = $this->getUserFacade()->createUser($data);

        $this->assertInstanceOf(UserTransfer::class, $user);
        $this->assertNotNull($user->getIdUser());
        $this->assertSame($data->getFirstName(), $user->getFirstName());
        $this->assertSame($data->getLastName(), $user->getLastName());
        $this->assertSame($data->getUsername(), $user->getUsername());
        $this->assertNotEquals($data->getPassword(), $user->getPassword());
    }

    /**
     * @return void
     */
    public function testAfterCallToRemoveUserGetUserByIdMustThrowAnException(): void
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
    public function testUpdateUserWithSamePassword(): void
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
        $this->assertSame($data2['firstName'], $user2->getFirstName());
        $this->assertSame($data2['lastName'], $user2->getLastName());
        $this->assertSame($data2['username'], $user2->getUsername());
        $this->assertNotEquals($user->getPassword(), $user2->getPassword());

        $this->assertTrue($this->getUserFacade()->isValidPassword($data['password'], $user2->getPassword()));
    }

    /**
     * When hash is present in the user it should not be rehashed.
     *
     * @return void
     */
    public function testUpdateUserWithSamePasswordHash(): void
    {
        $data = $this->mockUserData();
        $user = $this->getUserFacade()->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);

        $user2 = clone $user;
        $user2 = $this->getUserFacade()->updateUser($user2);

        $hashedPassword = $user->getPassword();
        $newHashedPassword = $user2->getPassword();
        $this->assertSame($hashedPassword, $newHashedPassword);

        $user2 = $this->getUserFacade()->updateUser($user2);
        $newHashedPassword = $user2->getPassword();
        $this->assertSame($hashedPassword, $newHashedPassword);
    }

    /**
     * @return void
     */
    public function testUpdateUserWithNewPassword(): void
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
        $this->assertSame($user->getFirstName(), $finalUser->getFirstName());
        $this->assertSame($user->getLastName(), $finalUser->getLastName());
        $this->assertSame($user->getUsername(), $finalUser->getUsername());
        $this->assertNotEquals($user->getPassword(), $finalUser->getPassword());

        $this->assertTrue($this->getUserFacade()->isValidPassword($data2['password'], $finalUser->getPassword()));
    }

    /**
     * @return void
     */
    public function testUpdateWithPasswordHashIgnored(): void
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
    public function testGetUserByUsername(): void
    {
        $data = $this->mockUserData();
        $mock = $this->mockAddUser($data);

        $user = $this->getUserFacade()->getUserByUsername($data['username']);

        $this->assertInstanceOf(UserTransfer::class, $user);
        $this->assertSame($user->getIdUser(), $mock->getIdUser());
        $this->assertSame($user->getFirstName(), $mock->getFirstName());
        $this->assertSame($user->getLastName(), $mock->getLastName());
        $this->assertSame($user->getUsername(), $mock->getUsername());
        $this->assertSame($user->getPassword(), $mock->getPassword());
    }

    /**
     * @return void
     */
    public function testGetUserById(): void
    {
        $data = $this->mockUserData();
        $mock = $this->mockAddUser($data);

        $user = $this->getUserFacade()->getUserById($mock->getIdUser());

        $this->assertInstanceOf(UserTransfer::class, $user);
        $this->assertSame($user->getIdUser(), $mock->getIdUser());
        $this->assertSame($user->getFirstName(), $mock->getFirstName());
        $this->assertSame($user->getLastName(), $mock->getLastName());
        $this->assertSame($user->getUsername(), $mock->getUsername());
        $this->assertSame($user->getPassword(), $mock->getPassword());
    }

    /**
     * @return void
     */
    public function testIsValidPassword(): void
    {
        $data = $this->mockUserData();
        $user = $this->mockAddUser($data);

        $this->assertTrue($this->getUserFacade()->isValidPassword($data['password'], $user->getPassword()));
    }

    /**
     * @return void
     */
    public function testUserTransferClonedBeforeStoringInSession(): void
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
    public function testUserTransferClonedAfterReadingFromSession(): void
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
    public function testHasCurrentUserReturnsFalseOnNull(): void
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
     * @dataProvider getUserPositiveScenarioDataProvider
     *
     * @param string[] $userCriteriaKeys
     *
     * @return void
     */
    public function testFindUserReturnsTransferWithCorrectData(array $userCriteriaKeys): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([
            UserTransfer::USERNAME => 'test_user@spryker.com',
        ]);

        $userCriteriaData = [
            UserCriteriaTransfer::ID_USER => $userTransfer->getIdUser(),
            UserCriteriaTransfer::EMAIL => $userTransfer->getUsername(),
        ];
        $userCriteriaData = array_intersect_key(
            $userCriteriaData,
            array_flip($userCriteriaKeys)
        );

        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->fromArray($userCriteriaData);

        // Act
        $foundUserTransfer = $this->tester
            ->getFacade()
            ->findUser($userCriteriaTransfer);

        // Assert
        $this->assertSame($userTransfer->getIdUser(), $foundUserTransfer->getIdUser());
    }

    /**
     * @dataProvider getUserNegativeScenarioDataProvider
     *
     * @param array $userCriteriaData
     *
     * @return void
     */
    public function testFindUserReturnsNullWithWrongData(array $userCriteriaData): void
    {
        // Arrange
        $this->tester->haveUser([
            UserTransfer::USERNAME => 'test_user@spryker.com',
        ]);

        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->fromArray($userCriteriaData);

        // Act
        $foundUserTransfer = $this->tester
            ->getFacade()
            ->findUser($userCriteriaTransfer);

        // Assert
        $this->assertNull($foundUserTransfer);
    }

    /**
     * @return void
     */
    public function testExpandMailWithUserDataReturnsUpdatedTransfer(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([
            UserTransfer::USERNAME => static::USERNAME,
        ]);
        $mailTransfer = $this->getMailTransfer($userTransfer->getUsername());

        // Act
        $expenseTransfer = $this->tester
            ->getFacade()
            ->expandMailWithUserData($mailTransfer);

        // Assert
        $this->assertSame($mailTransfer->getUser()->getIdUser(), $userTransfer->getIdUser());
    }

    /**
     * @return void
     */
    public function testExpandMailWithUserDataDoesNothingWithIncorrectData(): void
    {
        // Arrange
        $mailTransfer = $this->getMailTransfer('nonexistent_email@mail.com');

        // Act
        $expenseTransfer = $this->tester
            ->getFacade()
            ->expandMailWithUserData($mailTransfer);

        // Assert
        $this->assertNull($mailTransfer->getUser());
    }

    /**
     * @param string $recipientEmail
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function getMailTransfer(string $recipientEmail): MailTransfer
    {
        return (new MailBuilder())
            ->seed()
            ->withRecipient([
                MailRecipientTransfer::EMAIL => $recipientEmail,
            ])
            ->build();
    }

    /**
     * @param string $userName
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function createUserTransfer(string $userName): UserTransfer
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Session\SessionClient
     */
    protected function createSessionClient(): SessionClient
    {
        $sessionClient = $this->getMockBuilder(SessionClient::class)->setMethods(['get', 'set', 'has'])->getMock();

        return $sessionClient;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\User\Persistence\UserQueryContainerInterface
     */
    protected function createQueryContainer(): UserQueryContainerInterface
    {
        $queryContainer = $this->getMockBuilder(UserQueryContainerInterface::class)->getMock();

        return $queryContainer;
    }

    /**
     * @return array
     */
    public function getUserPositiveScenarioDataProvider(): array
    {
        return [
            'by id user' => [
                'userCriteriaDataKeys' => [
                    UserCriteriaTransfer::ID_USER,
                ],
            ],
            'by email' => [
                'userCriteriaDataKeys' => [
                    UserCriteriaTransfer::EMAIL,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getUserNegativeScenarioDataProvider(): array
    {
        return [
            'by id user' => [
                'userCriteriaData' => [
                    UserCriteriaTransfer::ID_USER => 0,
                ],
            ],
            'by email' => [
                'userCriteriaData' => [
                    UserCriteriaTransfer::EMAIL => '',
                ],
            ],
        ];
    }
}
