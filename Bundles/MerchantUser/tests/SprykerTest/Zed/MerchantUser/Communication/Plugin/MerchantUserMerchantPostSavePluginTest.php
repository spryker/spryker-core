<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantUser\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MerchantUser\Business\Exception\MerchantUserNotCreatedException;
use Spryker\Zed\MerchantUser\Communication\Plugin\Merchant\MerchantUserMerchantPostSavePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantUser
 * @group Communication
 * @group Plugin
 * @group MerchantUserMerchantPostSavePluginTest
 * Add your own group annotations below this line
 */
class MerchantUserMerchantPostSavePluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantUser\MerchantUserBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMerchantUserPostSaveReturnsSuccessIfUserDoesNotExist(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        $merchantUserMerchantPostSavePlugin = new MerchantUserMerchantPostSavePlugin();

        // Act
        $merchantTransfer = $merchantUserMerchantPostSavePlugin->execute($merchantTransfer);

        // Assert
        $this->assertNotEmpty($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testMerchantUserPostSaveThrowsExceptionIfUserAlreadyConnectedToAnotherMerchant(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'test@example.com']);

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => 'test2@example.com']);
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $merchantTransferWithSameEmail = $this->tester->haveMerchant([MerchantTransfer::EMAIL => $userTransfer->getUsername()]);
        $merchantTransferWithSameEmail->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransferWithSameEmail));

        $merchantUserMerchantPostSavePlugin = new MerchantUserMerchantPostSavePlugin();

        // Assert
        $this->expectException(MerchantUserNotCreatedException::class);

        // Act
        $merchantUserMerchantPostSavePlugin->execute($merchantTransferWithSameEmail);
    }
}
