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
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
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

        $this->authFacadeMock = $this->getMockBuilder(MerchantUserToAuthFacadeInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['requestPasswordReset'])
            ->getMockForAbstractClass();

        $this->userFacadeMock = $this->getMockBuilder(MerchantUserToUserFacadeInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getUserById', 'updateUser'])
            ->getMockForAbstractClass();
    }

    /**
     * @return void
     */
    public function testCreateMerchantAdminReturnsTrueIfUserDoesNotExist(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->createMerchantAdmin($merchantTransfer);
        $merchantUserEntity = $this->tester->findMerchantUser(
            (new MerchantUserCriteriaFilterTransfer())->setIdMerchant($merchantTransfer->getIdMerchant())
        );

        // Assert
        $this->assertTrue($merchantUserResponseTransfer->getIsSuccessful());
        $this->assertNotEmpty($merchantUserEntity);
    }

    /**
     * @return void
     */
    public function testCreateMerchantAdminReturnsTrueIfUserExist(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'test@example.com']);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => $userTransfer->getUsername()]);
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->createMerchantAdmin($merchantTransfer);
        $merchantUserEntity = $this->tester->findMerchantUser(
            (new MerchantUserCriteriaFilterTransfer())->setIdMerchant($merchantTransfer->getIdMerchant())
        );

        // Assert
        $this->assertTrue($merchantUserResponseTransfer->getIsSuccessful());
        $this->assertNotEmpty($merchantUserEntity);
    }

    /**
     * @return void
     */
    public function testCreateMerchantAdminReturnsFalseIfUserAlreadyConnectedToAnotherMerchant(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'test@example.com']);

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => 'test3@example.com']);
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $merchantTransferWithSameEmail = $this->tester->haveMerchant([MerchantTransfer::EMAIL => $userTransfer->getUsername()]);
        $merchantTransferWithSameEmail->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransferWithSameEmail));

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->createMerchantAdmin($merchantTransferWithSameEmail);
        $merchantUserEntity = $this->tester->findMerchantUser(
            (new MerchantUserCriteriaFilterTransfer())->setIdMerchant($merchantTransferWithSameEmail->getIdMerchant())
        );

        // Assert
        $this->assertFalse($merchantUserResponseTransfer->getIsSuccessful());
        $this->assertEmpty($merchantUserEntity);
    }

    /**
     * @return void
     */
    public function testUpdateMerchantAdmin(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'test@example.com']);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => $userTransfer->getUsername()]);
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        // Act
        $this->tester->getFacade()->createMerchantAdmin($merchantTransfer);
        $merchantTransfer->setEmail('test2@examle.com');
        $merchantUserResponseTransfer = $this->tester->getFacade()->updateMerchantAdmin($merchantTransfer);

        // Assert
        $this->assertTrue($merchantUserResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testHandleMerchantPostUpdateUserStatusChangedToApprovedOnMerchantStatusChangedToApprove(): void
    {
        // Arrange
        $this->initializeFacadeMocks();

        $userTransfer = $this->tester->haveUser(
            [UserTransfer::USERNAME => 'test_user@example.com']
        );

        $merchantTransfer = $this->tester->haveMerchant(
            [MerchantTransfer::EMAIL => $userTransfer->getUsername(), MerchantTransfer::STATUS => 'approved']
        );

        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $this->userFacadeMock->expects($this->once())->method('getUserById')
            ->willReturn($userTransfer->setStatus(self::USER_STATUS_BLOCKED));

        $this->userFacadeMock->expects($this->once())->method('updateUser')
            ->willReturn($userTransfer);

        $this->authFacadeMock->expects($this->never())->method('requestPasswordReset');

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()
            ->updateMerchantAdmin($merchantTransfer);

        // Assert
        $this->assertTrue($merchantResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testHandleMerchantPostUpdateUserStatusChangedToDeniedOnMerchantStatusChangeToBlocked(): void
    {
        // Arrange
        $this->initializeFacadeMocks();

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
        $merchantResponseTransfer = $this->tester->getFacade()
            ->updateMerchantAdmin($merchantTransfer);

        // Assert
        $this->assertTrue($merchantResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    protected function initializeFacadeMocks(): void
    {
        $this->tester->setDependency(MerchantUserDependencyProvider::FACADE_AUTH, $this->authFacadeMock);
        $this->tester->setDependency(MerchantUserDependencyProvider::FACADE_USER, $this->userFacadeMock);
    }
}
