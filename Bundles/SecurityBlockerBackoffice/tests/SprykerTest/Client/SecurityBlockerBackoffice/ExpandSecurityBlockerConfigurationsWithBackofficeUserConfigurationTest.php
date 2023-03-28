<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SecurityBlockerBackoffice;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SecurityBlockerBackoffice
 * @group ExpandSecurityBlockerConfigurationsWithBackofficeUserConfigurationTest
 * Add your own group annotations below this line
 */
class ExpandSecurityBlockerConfigurationsWithBackofficeUserConfigurationTest extends Unit
{
    /**
     * @uses {@link \Spryker\Client\SecurityBlockerBackoffice\SecurityBlockerBackofficeConfig::BACKOFFICE_USER_SECURITY_BLOCKER_ENTITY_TYPE}
     *
     * @var string
     */
    protected const SECURITY_BLOCKER_ENTITY_TYPE = 'back-office-user';

    /**
     * @var \SprykerTest\Client\SecurityBlockerBackoffice\SecurityBlockerBackofficeClientTester
     */
    protected SecurityBlockerBackofficeClientTester $tester;

    /**
     * @return void
     */
    public function testExpandSecurityBlockerConfigurationsWithBackofficeUserConfigurationShouldReturnCorrectSettingTransfers(): void
    {
        // Act
        $securityBlockerConfigurationSettingsTransfers = $this->tester->getClient()->expandSecurityBlockerConfigurationsWithBackofficeUserConfiguration([]);

        // Assert
        $this->assertCount(1, $securityBlockerConfigurationSettingsTransfers);
        $this->assertSame(static::SECURITY_BLOCKER_ENTITY_TYPE, array_keys($securityBlockerConfigurationSettingsTransfers)[0]);
    }
}
