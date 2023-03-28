<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SecurityBlockerStorefrontCustomer;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SecurityBlockerStorefrontCustomer
 * @group ExpandSecurityBlockerConfigurationsWithCustomerConfigurationTest
 * Add your own group annotations below this line
 */
class ExpandSecurityBlockerConfigurationsWithCustomerConfigurationTest extends Unit
{
    /**
     * @uses {@link \Spryker\Client\SecurityBlockerStorefrontCustomer\SecurityBlockerStorefrontCustomerConfig::SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE}
     *
     * @var string
     */
    protected const SECURITY_BLOCKER_ENTITY_TYPE = 'customer';

    /**
     * @var \SprykerTest\Client\SecurityBlockerStorefrontCustomer\SecurityBlockerStorefrontCustomerClientTester
     */
    protected SecurityBlockerStorefrontCustomerClientTester $tester;

    /**
     * @return void
     */
    public function testExpandSecurityBlockerConfigurationsWithCustomerConfigurationShouldReturnCorrectSettingTransfers(): void
    {
        // Act
        $securityBlockerConfigurationSettingsTransfers = $this->tester->getClient()->expandSecurityBlockerConfigurationsWithCustomerConfiguration([]);

        //Assert
        $this->assertCount(1, $securityBlockerConfigurationSettingsTransfers);
        $this->assertSame(static::SECURITY_BLOCKER_ENTITY_TYPE, array_keys($securityBlockerConfigurationSettingsTransfers)[0]);
    }
}
