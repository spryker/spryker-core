<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\User\Business\Model;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\Session\SessionClient;
use Spryker\Zed\User\Business\Model\User;
use Spryker\Zed\User\Persistence\UserQueryContainerInterface;
use Spryker\Zed\User\UserConfig;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group User
 * @group Business
 * @group Model
 * @group UserTest
 */
class UserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @const string
     */
    const USERNAME = 'test@test.com';

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
            ->method('get')
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
        $sessionClient = $this->getMock(
            SessionClient::class,
            ['get', 'set']
        );

        return $sessionClient;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\User\Persistence\UserQueryContainerInterface
     */
    protected function createQueryContainer()
    {
        $queryContainer = $this->getMock(UserQueryContainerInterface::class);

        return $queryContainer;
    }

}
