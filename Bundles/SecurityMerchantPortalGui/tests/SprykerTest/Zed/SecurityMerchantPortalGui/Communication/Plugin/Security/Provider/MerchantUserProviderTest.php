<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Provider;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Exception\AccessDeniedException;
use Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiDependencyProvider;
use SprykerTest\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiCommunicationTester;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityMerchantPortalGui
 * @group Communication
 * @group Plugin
 * @group Security
 * @group Provider
 * @group MerchantUserProviderTest
 * Add your own group annotations below this line
 */
class MerchantUserProviderTest extends Unit
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
     * @var \SprykerTest\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiCommunicationTester
     */
    protected SecurityMerchantPortalGuiCommunicationTester $tester;

    /**
     * @return void
     */
    public function testLoadUserByUsernameThrowsExceptionWhenLoginIsRestrictedByMerchantUserLoginRestrictionPlugin(): void
    {
        // Arrange
        $this->tester->setDependency(
            SecurityMerchantPortalGuiDependencyProvider::PLUGINS_MERCHANT_USER_LOGIN_RESTRICTION,
            [
                $this->tester->createMerchantUserLoginRestrictionPluginMock(
                    [
                        'isRestricted' => true,
                    ],
                ),
            ],
        );

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED]);
        $userTransfer = $this->tester->haveUser([UserTransfer::STATUS => static::USER_STATUS_ACTIVE]);
        $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        // Assert
        $this->expectException(AccessDeniedException::class);

        // Act
        $this->tester->getUser(
            $this->tester->getFactory()->createMerchantUserProvider(),
            $userTransfer->getUsernameOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testLoadUserByUsernameDoesNotThrowExceptionWhenLoginIsNotRestrictedByMerchantUserLoginRestrictionPlugin(): void
    {
        // Arrange
        $this->tester->setDependency(
            SecurityMerchantPortalGuiDependencyProvider::PLUGINS_MERCHANT_USER_LOGIN_RESTRICTION,
            [
                $this->tester->createMerchantUserLoginRestrictionPluginMock(
                    [
                        'isRestricted' => false,
                    ],
                ),
            ],
        );

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED]);
        $userTransfer = $this->tester->haveUser([UserTransfer::STATUS => static::USER_STATUS_ACTIVE]);
        $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        // Act
        $user = $this->tester->getUser(
            $this->tester->getFactory()->createMerchantUserProvider(),
            $userTransfer->getUsernameOrFail(),
        );

        // Assert
        $this->assertInstanceOf(UserInterface::class, $user);
    }
}
