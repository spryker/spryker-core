<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\AgentSecurityBlockerMerchantPortal;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group AgentSecurityBlockerMerchantPortal
 * @group ExpandSecurityBlockerConfigurationsWithAgentMerchantPortalConfigurationTest
 * Add your own group annotations below this line
 */
class ExpandSecurityBlockerConfigurationsWithAgentMerchantPortalConfigurationTest extends Unit
{
    /**
     * @uses {@link \Spryker\Client\AgentSecurityBlockerMerchantPortal\AgentSecurityBlockerMerchantPortalConfig::AGENT_MERCHANT_PORTAL_ENTITY_TYPE}
     *
     * @var string
     */
    protected const SECURITY_BLOCKER_ENTITY_TYPE = 'agent-merchant-portal';

    /**
     * @var \SprykerTest\Client\AgentSecurityBlockerMerchantPortal\AgentSecurityBlockerMerchantPortalClientTester
     */
    protected AgentSecurityBlockerMerchantPortalClientTester $tester;

    /**
     * @return void
     */
    public function testExpandSecurityBlockerConfigurationsWithAgentMerchantPortalConfigurationShouldReturnCorrectSettingTransfers(): void
    {
        // Act
        $securityBlockerConfigurationSettingsTransfers = $this->tester->getClient()->expandSecurityBlockerConfigurationsWithAgentMerchantPortalConfiguration([]);

        // Assert
        $this->assertCount(1, $securityBlockerConfigurationSettingsTransfers);
        $this->assertSame(static::SECURITY_BLOCKER_ENTITY_TYPE, array_keys($securityBlockerConfigurationSettingsTransfers)[0]);
    }
}
