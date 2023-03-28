<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SecurityBlockerStorefrontAgent;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SecurityBlockerStorefrontAgent
 * @group ExpandSecurityBlockerConfigurationsWithAgentConfigurationTest
 * Add your own group annotations below this line
 */
class ExpandSecurityBlockerConfigurationsWithAgentConfigurationTest extends Unit
{
    /**
     * @uses {@link \Spryker\Client\SecurityBlockerStorefrontAgent\SecurityBlockerStorefrontAgentConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE}
     *
     * @var string
     */
    protected const SECURITY_BLOCKER_ENTITY_TYPE = 'agent';

    /**
     * @var \SprykerTest\Client\SecurityBlockerStorefrontAgent\SecurityBlockerStorefrontAgentClientTester
     */
    protected SecurityBlockerStorefrontAgentClientTester $tester;

    /**
     * @return void
     */
    public function testExpandSecurityBlockerConfigurationsWithAgentConfigurationShouldReturnCorrectSettingTransfers(): void
    {
        // Act
        $securityBlockerConfigurationSettingsTransfers = $this->tester->getClient()->expandSecurityBlockerConfigurationsWithAgentConfiguration([]);

        //Assert
        $this->assertCount(1, $securityBlockerConfigurationSettingsTransfers);
        $this->assertSame(static::SECURITY_BLOCKER_ENTITY_TYPE, array_keys($securityBlockerConfigurationSettingsTransfers)[0]);
    }
}
