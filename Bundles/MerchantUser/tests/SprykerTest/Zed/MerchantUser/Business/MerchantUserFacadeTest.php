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
    public function testCreateByMerchantReturnsTrueIfUserDoesNotExist(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->createByMerchant($merchantTransfer);

        // Assert
        $this->assertTrue($merchantUserResponseTransfer->getIsSuccess());
        $this->assertSame($merchantUserResponseTransfer->getMerchantUser()->getIdMerchant(), $merchantTransfer->getIdMerchant());
    }

    /**
     * @return void
     */
    public function testCreateByMerchantReturnsTrueIfUserExist(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'test@example.com']);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => $userTransfer->getUsername()]);
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->createByMerchant($merchantTransfer);

        // Assert
        $this->assertTrue($merchantUserResponseTransfer->getIsSuccess());
        $this->assertSame($merchantUserResponseTransfer->getMerchantUser()->getIdMerchant(), $merchantTransfer->getIdMerchant());
        $this->assertSame($merchantUserResponseTransfer->getMerchantUser()->getIdUser(), $userTransfer->getIdUser());
    }

    /**
     * @return void
     */
    public function testCreateByMerchantReturnsFalseIfUserAlreadyConnectedToAnotherMerchant(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'test@example.com']);

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => 'test2@example.com']);
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $merchantTransferWithSameEmail = $this->tester->haveMerchant([MerchantTransfer::EMAIL => $userTransfer->getUsername()]);
        $merchantTransferWithSameEmail->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransferWithSameEmail));

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->createByMerchant($merchantTransferWithSameEmail);

        // Assert
        $this->assertFalse($merchantUserResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateByMerchant(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'test@example.com']);
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => $userTransfer->getUsername()]);
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        // Act
        $merchantUserResponseTransfer = $this->tester->getFacade()->createByMerchant($merchantTransfer);
        $merchantTransfer->setEmail('test2@examle.com');
        $merchantUserResponseTransfer = $this->tester->getFacade()->updateByMerchant($merchantUserResponseTransfer->getMerchantUser(), $merchantTransfer);

        // Assert
        $this->assertTrue($merchantUserResponseTransfer->getIsSuccess());
        $this->assertSame($merchantUserResponseTransfer->getMerchantUser()->getIdMerchant(), $merchantTransfer->getIdMerchant());
        $this->assertSame($merchantUserResponseTransfer->getMerchantUser()->getUser()->getUsername(), $merchantTransfer->getEmail());
        $this->assertSame($merchantUserResponseTransfer->getMerchantUser()->getIdUser(), $userTransfer->getIdUser());
    }

    /**
     * @return void
     */
    public function testFindMerchantUser(): void
    {
        // Arrange
        $merchantUser = $this->tester->haveMerchantUser(
            $this->tester->haveMerchant([MerchantTransfer::EMAIL => 'test@example.com']),
            $this->tester->haveUser([UserTransfer::USERNAME => 'test@example.com'])
        );

        $merchantUserCriteriaFilterTransfer = (new MerchantUserCriteriaFilterTransfer())
            ->setIdMerchant($merchantUser->getIdMerchant())
            ->setIdUser($merchantUser->getIdUser());

        // Act
        $merchantUserFromFacade = $this->tester->getFacade()->findOne($merchantUserCriteriaFilterTransfer);

        // Assert
        $this->assertSame($merchantUser->getIdMerchant(), $merchantUserFromFacade->getIdMerchant());
    }
}
