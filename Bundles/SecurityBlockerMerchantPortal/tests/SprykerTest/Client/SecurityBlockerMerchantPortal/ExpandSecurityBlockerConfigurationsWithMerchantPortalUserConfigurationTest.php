<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SecurityBlockerMerchantPortal;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SecurityBlockerMerchantPortal
 * @group ExpandSecurityBlockerConfigurationsWithMerchantPortalUserConfigurationTest
 * Add your own group annotations below this line
 */
class ExpandSecurityBlockerConfigurationsWithMerchantPortalUserConfigurationTest extends Unit
{
    /**
     * @uses {@link \Spryker\Client\SecurityBlockerMerchantPortal\SecurityBlockerMerchantPortalConfig::MERCHANT_PORTAL_USER_ENTITY_TYPE}
     *
     * @var string
     */
    protected const SECURITY_BLOCKER_ENTITY_TYPE = 'merchant-portal-user';

    /**
     * @var \SprykerTest\Client\SecurityBlockerMerchantPortal\SecurityBlockerMerchantPortalClientTester
     */
    protected SecurityBlockerMerchantPortalClientTester $tester;

    /**
     * @return void
     */
    public function testExpandSecurityBlockerConfigurationsWithMerchantPortalUserConfigurationShouldReturnCorrectSettingTransfers(): void
    {
        // Act
        $securityBlockerConfigurationSettingsTransfers = $this->tester->getClient()->expandSecurityBlockerConfigurationsWithMerchantPortalUserConfiguration([]);

        // Assert
        $this->assertCount(1, $securityBlockerConfigurationSettingsTransfers);
        $this->assertSame(static::SECURITY_BLOCKER_ENTITY_TYPE, array_keys($securityBlockerConfigurationSettingsTransfers)[0]);
    }
}
