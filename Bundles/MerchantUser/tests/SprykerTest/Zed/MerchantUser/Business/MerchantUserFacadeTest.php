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
     * @var \SprykerTest\Zed\MerchantUser\MerchantUserBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testHandleMerchantPostCreateReturnsTrueIfUserDoesNotExist(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->createMerchantAdmin($merchantTransfer);
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
        $merchantResponseTransfer = $this->tester->getFacade()->createMerchantAdmin($merchantTransfer);
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
        $merchantResponseTransfer = $this->tester->getFacade()->createMerchantAdmin($merchantTransferWithSameEmail);
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
        $this->tester->getFacade()->createMerchantAdmin($merchantTransfer);
        $merchantTransfer->setEmail('test2@examle.com');
        $merchantResponseTransfer = $this->tester->getFacade()->updateMerchantAdmin($merchantTransfer);

        // Assert
        $this->assertTrue($merchantResponseTransfer->getIsSuccess());
    }
}
