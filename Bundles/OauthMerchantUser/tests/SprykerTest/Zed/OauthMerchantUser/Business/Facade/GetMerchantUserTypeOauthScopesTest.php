<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthMerchantUser\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserIdentifierTransfer;
use Generated\Shared\Transfer\UserTransfer;
use SprykerTest\Zed\OauthMerchantUser\OauthMerchantUserTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthMerchantUser
 * @group Business
 * @group Facade
 * @group GetMerchantUserTypeOauthScopesTest
 * Add your own group annotations below this line
 */
class GetMerchantUserTypeOauthScopesTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @var string
     */
    protected const USER_STATUS_ACTIVE = 'active';

    /**
     * @uses \Spryker\Zed\OauthMerchantUser\OauthMerchantUserConfig::SCOPE_MERCHANT_USER
     *
     * @var string
     */
    protected const SCOPE_MERCHANT_USER = 'merchant-user';

    /**
     * @var \SprykerTest\Zed\OauthMerchantUser\OauthMerchantUserTester
     */
    protected OauthMerchantUserTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnConfiguratedMerchantUserScopes(): void
    {
        // Arrange
        $merchantUserTransfer = $this->createMerchantUser();
        $userIdentifierTransfer = (new UserIdentifierTransfer())->setIdUser($merchantUserTransfer->getIdUserOrFail());

        // Act
        $oauthScopeTransfers = $this->tester->getFacade()->getMerchantUserTypeOauthScopes($userIdentifierTransfer);

        // Assert
        $this->assertSame($oauthScopeTransfers[0]->getIdentifier(), static::SCOPE_MERCHANT_USER);
    }

    /**
     * @return void
     */
    public function testShouldNotReturnWarehouseUserScopesWhileUserIsUnknown(): void
    {
        // Arrange
        $userIdentifierTransfer = (new UserIdentifierTransfer())->setIdUser(666);

        // Act
        $oauthScopeTransfers = $this->tester->getFacade()->getMerchantUserTypeOauthScopes($userIdentifierTransfer);

        // Assert
        $this->assertEmpty($oauthScopeTransfers);
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    protected function createMerchantUser(): MerchantUserTransfer
    {
        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED]);
        $userTransfer = $this->tester->haveUser([UserTransfer::STATUS => static::USER_STATUS_ACTIVE]);

        return $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);
    }
}
