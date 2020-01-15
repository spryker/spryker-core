<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantUser\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
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
    public function testCreateMerchantUserByMerchantReturnsTrueIfUserDoesNotExist(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->createMerchantUserByMerchant($merchantTransfer);

        // Assert
        $this->assertTrue($merchantUserResponseTransfer->getIsSuccess());
        $this->assertSame($merchantUserResponseTransfer->getMerchantUser()->getIdMerchant(), $merchantTransfer->getIdMerchant());
    }

    /**
     * @return void
     */
    public function testCreateMerchantUserByMerchantReturnsTrueIfUserExist(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'test@example.com']);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => $userTransfer->getUsername()]);
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->createMerchantUserByMerchant($merchantTransfer);

        // Assert
        $this->assertTrue($merchantUserResponseTransfer->getIsSuccess());
        $this->assertSame($merchantUserResponseTransfer->getMerchantUser()->getIdMerchant(), $merchantTransfer->getIdMerchant());
        $this->assertSame($merchantUserResponseTransfer->getMerchantUser()->getIdUser(), $userTransfer->getIdUser());
    }

    /**
     * @return void
     */
    public function testCreateMerchantUserByMerchantUpdatesUserEmail(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'test@example.com']);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => $userTransfer->getUsername()]);
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        // Act
        $this->tester->getFacade()->createMerchantUserByMerchant($merchantTransfer);
        $merchantTransfer->setEmail('test2@examle.com');
        $merchantUserResponseTransfer = $this->tester->getFacade()->createMerchantUserByMerchant($merchantTransfer);

        // Assert
        $this->assertTrue($merchantUserResponseTransfer->getIsSuccess());
        $this->assertSame($merchantUserResponseTransfer->getMerchantUser()->getIdMerchant(), $merchantTransfer->getIdMerchant());
        $this->assertSame($merchantUserResponseTransfer->getMerchantUser()->getUser()->getUsername(), $merchantTransfer->getEmail());
        $this->assertSame($merchantUserResponseTransfer->getMerchantUser()->getIdUser(), $userTransfer->getIdUser());
    }

    /**
     * @return void
     */
    public function testCreateMerchantUserByMerchantReturnsFalseIfUserAlreadyConnectedToAnotherMerchant(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'test@example.com']);

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => 'test2@example.com']);
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $merchantTransferWithSameEmail = $this->tester->haveMerchant([MerchantTransfer::EMAIL => $userTransfer->getUsername()]);
        $merchantTransferWithSameEmail->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransferWithSameEmail));

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->createMerchantUserByMerchant($merchantTransferWithSameEmail);

        // Assert
        $this->assertFalse($merchantUserResponseTransfer->getIsSuccess());
    }
}
