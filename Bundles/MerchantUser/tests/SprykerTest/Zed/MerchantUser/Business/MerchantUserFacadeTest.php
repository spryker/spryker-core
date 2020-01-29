<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantUser\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeBridge;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeBridge;
use Spryker\Zed\MerchantUser\MerchantUserDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantUser
 * @group Business
 * @group Facade
 * @group MerchantUserFacadeTest
 * Add your own group annotations below this line
 */
class MerchantUserFacadeTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeBridge
     */
    protected $userFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeBridge
     */
    protected $authFacadeMock;

    /**
     * @var \SprykerTest\Zed\MerchantUser\MerchantUserBusinessTester
     */
    protected $tester;

    /**
     * @see \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_BLOCKED
     */
    protected const USER_STATUS_BLOCKED = 'blocked';

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->authFacadeMock = $this->getMockBuilder(MerchantUserToAuthFacadeBridge::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['requestPasswordReset'])
            ->getMock();

        $this->userFacadeMock = $this->getMockBuilder(MerchantUserToUserFacadeBridge::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getUserById', 'updateUser'])
            ->getMock();
    }

    /**
     * @return void
     */
    public function testHandleMerchantPostCreateReturnsTrueIfUserDoesNotExist(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->handleMerchantPostCreate($merchantTransfer);
        $merchantUserEntity = $this->tester->findMerchantUser(
            (new MerchantUserCriteriaFilterTransfer())->setIdMerchant($merchantTransfer->getIdMerchant())
        );

        // Assert
        $this->assertTrue($merchantResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($merchantUserEntity);
    }

    /**
     * @return void
     */
    public function testHandleMerchantPostCreateReturnsTrueIfUserExist(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'test@example.com']);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => $userTransfer->getUsername()]);
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->handleMerchantPostCreate($merchantTransfer);
        $merchantUserEntity = $this->tester->findMerchantUser(
            (new MerchantUserCriteriaFilterTransfer())->setIdMerchant($merchantTransfer->getIdMerchant())
        );

        // Assert
        $this->assertTrue($merchantResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($merchantUserEntity);
    }

    /**
     * @return void
     */
    public function testHandleMerchantPostCreateReturnsFalseIfUserAlreadyConnectedToAnotherMerchant(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'test@example.com']);

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => 'test3@example.com']);
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $merchantTransferWithSameEmail = $this->tester->haveMerchant([MerchantTransfer::EMAIL => $userTransfer->getUsername()]);
        $merchantTransferWithSameEmail->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransferWithSameEmail));

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->handleMerchantPostCreate($merchantTransferWithSameEmail);
        $merchantUserEntity = $this->tester->findMerchantUser(
            (new MerchantUserCriteriaFilterTransfer())->setIdMerchant($merchantTransferWithSameEmail->getIdMerchant())
        );

        // Assert
        $this->assertFalse($merchantResponseTransfer->getIsSuccess());
        $this->assertEmpty($merchantUserEntity);
    }

    /**
     * @return void
     */
    public function testHandleMerchantPostUpdate(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'test@example.com']);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => $userTransfer->getUsername()]);
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->handleMerchantPostCreate($merchantTransfer);
        $merchantTransfer->setEmail('test2@examle.com');
        $merchantResponseTransfer = $this->tester->getFacade()->handleMerchantPostUpdate($merchantTransfer, $merchantTransfer);

        // Assert
        $this->assertTrue($merchantResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUserStatusChangedToApprovedOnMerchantStatusChange(): void
    {
        // Arrange
        $this->tester->setDependency(MerchantUserDependencyProvider::FACADE_AUTH, $this->authFacadeMock);
        $this->tester->setDependency(MerchantUserDependencyProvider::FACADE_USER, $this->userFacadeMock);

        $userTransfer = $this->tester->haveUser(
            [UserTransfer::USERNAME => 'test_user@example.com']
        );

        $merchantTransfer = $this->tester->haveMerchant(
            [MerchantTransfer::EMAIL => $userTransfer->getUsername(), MerchantTransfer::STATUS => 'approved']
        );

        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $authFacadeMock = $this->getMockBuilder(MerchantUserToAuthFacadeBridge::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['requestPasswordReset'])
            ->getMock();

        $userFacadeMock = $this->getMockBuilder(MerchantUserToUserFacadeBridge::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getUserById', 'updateUser'])
            ->getMock();

        $this->userFacadeMock->expects($this->once())->method('getUserById')
            ->willReturn($userTransfer->setStatus(self::USER_STATUS_BLOCKED));

        $this->userFacadeMock->expects($this->once())->method('updateUser')
            ->willReturn($userTransfer);

        $this->authFacadeMock->expects($this->never())->method('requestPasswordReset');

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->handleMerchantPostUpdate($merchantTransfer, $merchantTransfer);

        // Assert
        $this->assertTrue($merchantResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUserStatusChangedToDeniedOnMerchantStatusChange(): void
    {
        // Arrange
        $this->tester->setDependency(MerchantUserDependencyProvider::FACADE_AUTH, $this->authFacadeMock);
        $this->tester->setDependency(MerchantUserDependencyProvider::FACADE_USER, $this->userFacadeMock);

        $userTransfer = $this->tester->haveUser(
            [UserTransfer::USERNAME => 'test_user@example.com']
        );

        $merchantTransfer = $this->tester->haveMerchant(
            [MerchantTransfer::EMAIL => $userTransfer->getUsername(), MerchantTransfer::STATUS => 'approved']
        );

        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $blockedUserTransfer = (clone $userTransfer)->setStatus(self::USER_STATUS_BLOCKED);

        $this->userFacadeMock->expects($this->once())->method('getUserById')
            ->willReturn($blockedUserTransfer);

        $this->userFacadeMock->expects($this->once())->method('updateUser')
            ->willReturn($userTransfer);

        $this->authFacadeMock->expects($this->once())->method('requestPasswordReset');

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->handleMerchantPostUpdate($merchantTransfer, $merchantTransfer);

        // Assert
        $this->assertTrue($merchantResponseTransfer->getIsSuccess());
    }
}
