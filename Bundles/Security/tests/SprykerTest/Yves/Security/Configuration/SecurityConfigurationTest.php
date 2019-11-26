<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Security\Configuration;

use Codeception\Test\Unit;
use Spryker\Shared\SecurityExtension\Configuration\SecurityConfiguration;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Security
 * @group Configuration
 * @group SecurityConfigurationTest
 * Add your own group annotations below this line
 */
class SecurityConfigurationTest extends Unit
{
    protected const FIREWALL_MAIN = 'main';
    protected const FIREWALL_SECONDARY = 'secondary';

    /**
     * @return void
     */
    public function testAddFirewallAddsAFirewallConfiguration(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall(static::FIREWALL_MAIN, []);

        $this->assertArrayHasKey(static::FIREWALL_MAIN, $securityConfiguration->getFirewalls());
    }

    /**
     * @return void
     */
    public function testAddFirewallAddsMultipleFirewallConfigurations(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall(static::FIREWALL_MAIN, []);
        $securityConfiguration->addFirewall(static::FIREWALL_SECONDARY, []);

        $this->assertCount(2, $securityConfiguration->getFirewalls());
    }

    /**
     * @return void
     */
    public function testMergeFirewallWillMergeRecursiveIfAFirewallIsAlreadyConfigured(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall(static::FIREWALL_MAIN, ['foo' => 'bar', 'zip' => 'zap']);
        $securityConfiguration->mergeFirewall(static::FIREWALL_MAIN, ['trip' => 'trap']);

        $firewalls = $securityConfiguration->getFirewalls();
        $this->assertArrayHasKey(static::FIREWALL_MAIN, $firewalls);
        $this->assertCount(1, $securityConfiguration->getFirewalls());
        $this->assertSame(['foo' => 'bar', 'zip' => 'zap', 'trip' => 'trap'], $firewalls[static::FIREWALL_MAIN]);
    }

    /**
     * @return void
     */
    public function testAddAccessRulesAddsAAccessRule(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addAccessRules(['foo' => 'bar']);

        $this->assertCount(1, $securityConfiguration->getAccessRules());
    }

    /**
     * @return void
     */
    public function testAddRoleHierarchyAddsARoleHierarchy(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addRoleHierarchy(['main' => []]);

        $this->assertCount(1, $securityConfiguration->getRoleHierarchies());
    }

    /**
     * @return void
     */
    public function testAddAuthenticationSuccessHandlerAddsAAuthenticationFailureHandler(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addAuthenticationSuccessHandler(static::FIREWALL_MAIN, function () {
        });

        $this->assertCount(1, $securityConfiguration->getAuthenticationSuccessHandlers());
    }

    /**
     * @return void
     */
    public function testAddAuthenticationFailureHandlerAddsAAuthenticationFailureHandler(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addAuthenticationFailureHandler(static::FIREWALL_MAIN, function () {
        });

        $this->assertCount(1, $securityConfiguration->getAuthenticationFailureHandlers());
    }

    /**
     * @return void
     */
    public function testAddAccessDeniedHandlerAddsAAccessDeniedHandler(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addAccessDeniedHandler(static::FIREWALL_MAIN, function () {
        });

        $this->assertCount(1, $securityConfiguration->getAccessDeniedHandlers());
    }

    /**
     * @return void
     */
    public function testAddEventSubscriberAddsAEventSubscriber(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addEventSubscriber(function () {
        });

        $this->assertCount(1, $securityConfiguration->getEventSubscribers());
    }
}
