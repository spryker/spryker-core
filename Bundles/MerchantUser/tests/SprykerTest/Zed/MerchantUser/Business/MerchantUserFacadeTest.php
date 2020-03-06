<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantUser\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUser;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
use Spryker\Zed\MerchantUser\MerchantUserDependencyProvider;
use Spryker\Zed\User\Business\Model\User;

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
     * @var \Generated\Shared\Transfer\MerchantUserTransfer
     */
    protected $merchantUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\UserTransfer
     */
    protected $newUserTransfer;

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
            ->onlyMethods(['getUserById', 'updateUser', 'createUser'])
            ->getMockForAbstractClass();

        $this->newUserTransfer = (new UserTransfer())
            ->setFirstName('test_merchant_user')
        ->setLastName('test_merchant_user')
        ->setUsername('test_merchant_user@spryker.com');

        $this->merchantUserTransfer = new MerchantUserTransfer();
    }

    /**
     * @return void
     */
    public function testCreateReturnsTrueIfUserDoesNotExist(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $this->merchantUserTransfer->setIdMerchant($merchantTransfer->getIdMerchant())->setUser($this->newUserTransfer);

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->create($this->merchantUserTransfer);
        $merchantUserEntity = $this->tester->findMerchantUser(
            (new MerchantUserCriteriaTransfer())->setIdMerchantUser($this->merchantUserTransfer->getIdMerchantUser())
        );

        // Assert
        $this->assertTrue($merchantUserResponseTransfer->getIsSuccessful());
        $this->assertInstanceOf(SpyMerchantUser::class, $merchantUserEntity);
    }

    /**
     * @return void
     */
    public function testCreateReturnsTrueIfUserExist(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => 'test_merchant_1@spryker.com']);

        $this->merchantUserTransfer->setIdMerchant($merchantTransfer->getIdMerchant())
            ->setUser($this->newUserTransfer);

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->create($this->merchantUserTransfer);
        $merchantUserEntity = $this->tester->findMerchantUser(
            (new MerchantUserCriteriaTransfer())->setIdMerchantUser($this->merchantUserTransfer->getIdMerchantUser())
        );

        // Assert
        $this->assertTrue($merchantUserResponseTransfer->getIsSuccessful());
        $this->assertInstanceOf(SpyMerchantUser::class, $merchantUserEntity);
    }

    /**
     * @return void
     */
    public function testCreateReturnsFalseIfUserAlreadyHasMerchant(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([
            UserTransfer::USERNAME => 'test_merchant_user@spryker.com',
        ]);

        $merchantOneTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => 'test_merchant_1@spryker.com']);
        $merchantTwoTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => 'test_merchant_2@spryker.com']);

        $this->tester->haveMerchantUser($merchantOneTransfer, $userTransfer);

        $this->merchantUserTransfer->setIdMerchant($merchantTwoTransfer->getIdMerchant())
            ->setUser($this->newUserTransfer);

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->create($this->merchantUserTransfer);

        // Assert
        $this->assertFalse($merchantUserResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            'A user with the same email is already connected to another merchant.',
            $merchantUserResponseTransfer->getErrors()[0]->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testUpdate(): void
    {
        // Arrange
        $this->initializeFacadeMocks();

        $userTransfer = $this->tester->haveUser([
            UserTransfer::USERNAME => 'test_merchant_user@spryker.com',
        ]);

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => 'test_merchant_1@spryker.com']);
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);
        $merchantUserTransfer->setUser($userTransfer);

        $this->userFacadeMock->expects($this->once())->method('getUserById')
            ->with($userTransfer->getIdUser())
            ->willReturn($userTransfer);

        $this->userFacadeMock->expects($this->once())->method('updateUser')
            ->with($userTransfer)
            ->willReturn($userTransfer);

        $this->authFacadeMock->expects($this->never())->method('requestPasswordReset');

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->update($merchantUserTransfer);

        // Assert
        $this->assertTrue($merchantUserResponseTransfer->getIsSuccessful());
    }

    public function testUpdateWithNewActiveStatus()
    {
        // Arrange
        $this->initializeFacadeMocks();

        $userTransfer = $this->tester->haveUser([
            UserTransfer::USERNAME => 'test_merchant_user@spryker.com'
        ])->setStatus('blocked');

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => 'test_merchant_1@spryker.com']);
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);
        $merchantUserTransfer->setUser($userTransfer);

        $this->userFacadeMock->expects($this->once())->method('getUserById')
            ->with($userTransfer->getIdUser())
            ->willReturn($userTransfer);

        $this->userFacadeMock->expects($this->once())->method('updateUser')
            ->with($userTransfer)
            ->willReturn($this->newUserTransfer->setStatus('active'));

        $this->authFacadeMock->expects($this->once())->method('requestPasswordReset');

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->update($merchantUserTransfer);

        // Assert
        $this->assertTrue($merchantUserResponseTransfer->getIsSuccessful());
    }

    public function testUpdateWithNewBlockedStatus()
    {
        // Arrange
        $this->initializeFacadeMocks();

        $userTransfer = $this->tester->haveUser([
            UserTransfer::USERNAME => 'test_merchant_user@spryker.com'
        ])->setStatus('blocked');

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => 'test_merchant_1@spryker.com']);
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);
        $merchantUserTransfer->setUser($userTransfer);

        $this->userFacadeMock->expects($this->once())->method('getUserById')
            ->with($userTransfer->getIdUser())
            ->willReturn($userTransfer);

        $this->userFacadeMock->expects($this->once())->method('updateUser')
            ->with($userTransfer)
            ->willReturn($this->newUserTransfer->setStatus('active'));

        $this->authFacadeMock->expects($this->once())->method('requestPasswordReset');

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->update($merchantUserTransfer);

        // Assert
        $this->assertTrue($merchantUserResponseTransfer->getIsSuccessful());
    }

    public function testFind()
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([
            UserTransfer::USERNAME => 'test_merchant_user@spryker.com'
        ]);

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => 'test_merchant_1@spryker.com']);
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        // Act
        $merchantUserTransferFromRequest = $this->tester->getFacade()->find(
            (new MerchantUserCriteriaTransfer())->setIdMerchantUser($merchantUserTransfer->getIdMerchantUser())
        );

        // Assert
        $this->assertEquals(
            $merchantUserTransfer->getIdMerchantUser(),
            $merchantUserTransferFromRequest->getIdMerchantUser()
        );
    }

    public function testDisableMerchantUsersByMerchantId()
    {
        // Arrange
        $this->initializeFacadeMocks();

        $userOneTransfer = $this->tester->haveUser([
            UserTransfer::USERNAME => 'test_merchant_user@spryker_one.com'
        ]);

        $userTwoTransfer = $this->tester->haveUser([
            UserTransfer::USERNAME => 'test_merchant_user@spryker_two.com'
        ]);

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => 'test_merchant_1@spryker.com']);

        $this->tester->haveMerchantUser($merchantTransfer, $userOneTransfer);
        $this->tester->haveMerchantUser($merchantTransfer, $userTwoTransfer);

        $this->userFacadeMock->expects($this->exactly(2))->method('getUserById')
            ->willReturnOnConsecutiveCalls($userOneTransfer, $userTwoTransfer);

        $this->userFacadeMock->expects($this->exactly(2))->method('updateUser')
            ->with(
                $this->callback(function (UserTransfer $userTransfer) {
                    return $userTransfer->getStatus() === 'blocked';
                })
            );

        // Act
        $this->tester->getFacade()->disableMerchantUsers(
            (new MerchantUserCriteriaTransfer())->setIdMerchant($merchantTransfer->getIdMerchant())
        );
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
