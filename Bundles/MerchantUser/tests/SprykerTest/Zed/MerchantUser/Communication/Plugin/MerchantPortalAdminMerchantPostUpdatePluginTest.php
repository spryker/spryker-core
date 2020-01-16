<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantUser\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MerchantUser\Communication\Plugin\Merchant\MerchantPortalAdminMerchantPostUpdatePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantUser
 * @group Communication
 * @group Plugin
 * @group MerchantPortalAdminMerchantPostUpdatePluginTest
 * Add your own group annotations below this line
 */
class MerchantPortalAdminMerchantPostUpdatePluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantUser\MerchantUserBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMerchantPortalAdminPostUpdateReturnsTrueIfUserDoesNotExist(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        $merchantPortalAdminMerchantPostUpdatePlugin = new MerchantPortalAdminMerchantPostUpdatePlugin();

        // Act
        $merchantResponseTransfer = $merchantPortalAdminMerchantPostUpdatePlugin->execute($merchantTransfer);

        // Assert
        $this->assertTrue($merchantResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testMerchantPortalAdminPostUpdateReturnsFalseIfUserAlreadyConnectedToAnotherMerchant(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([UserTransfer::USERNAME => 'test@example.com']);

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::EMAIL => 'test2@example.com']);
        $merchantTransfer->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransfer));

        $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $merchantTransferWithSameEmail = $this->tester->haveMerchant([MerchantTransfer::EMAIL => $userTransfer->getUsername()]);
        $merchantTransferWithSameEmail->setMerchantProfile($this->tester->haveMerchantProfile($merchantTransferWithSameEmail));

        $merchantPortalAdminMerchantPostUpdatePlugin = new MerchantPortalAdminMerchantPostUpdatePlugin();

        // Act
        $merchantResponseTransfer = $merchantPortalAdminMerchantPostUpdatePlugin->execute($merchantTransferWithSameEmail);

        // Assert
        $this->assertFalse($merchantResponseTransfer->getIsSuccess());
    }
}
