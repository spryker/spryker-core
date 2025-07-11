<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Security\Configuration;

use Codeception\Test\Unit;
use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use Spryker\Shared\Security\Exception\FirewallNotFoundException;
use Spryker\Shared\Security\Exception\SecurityConfigurationException;
use Symfony\Component\Security\Http\Event\LogoutEvent;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Security
 * @group Configuration
 * @group SecurityConfigurationTest
 * Add your own group annotations below this line
 */
class SecurityConfigurationTest extends Unit
{
    /**
     * @var string
     */
    protected const FIREWALL_MAIN = 'main';

    /**
     * @var string
     */
    protected const FIREWALL_SECONDARY = 'secondary';

    /**
     * @var string
     */
    protected const TEST_FIREWALL_CONFIGURATION = 'test';

    /**
     * @var string
     */
    protected const TEST_FIREWALL_CONFIGURATION_2 = 'test_2';

    /**
     * @var string
     */
    protected const TEST_FIREWALL_CONFIGURATION_3 = 'test_3';

    /**
     * @return void
     */
    public function testAddFirewallAddsAFirewallConfiguration(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall(static::FIREWALL_MAIN, []);
        $securityConfiguration = $securityConfiguration->getConfiguration();

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
        $securityConfiguration = $securityConfiguration->getConfiguration();

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
        $securityConfiguration = $securityConfiguration->getConfiguration();

        $firewalls = $securityConfiguration->getFirewalls();

        $this->assertArrayHasKey(static::FIREWALL_MAIN, $firewalls);
        $this->assertCount(1, $securityConfiguration->getFirewalls());
        $this->assertSame(['foo' => 'bar', 'zip' => 'zap', 'trip' => 'trap'], $firewalls[static::FIREWALL_MAIN]);
    }

    /**
     * @return void
     */
    public function testMergeFirewallWhichIsNotConfiguredWillThrowAnException(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->mergeFirewall(static::FIREWALL_MAIN, ['trip' => 'trap']);

        $this->expectException(FirewallNotFoundException::class);
        $securityConfiguration->getConfiguration();
    }

    /**
     * @return void
     */
    public function testMergeFirewallShouldNotRemoveAlreadyAddedConfiguration(): void
    {
        // Arrange
        $securityConfiguration = (new SecurityConfiguration())->addFirewall(static::FIREWALL_MAIN, [
            static::TEST_FIREWALL_CONFIGURATION => static::TEST_FIREWALL_CONFIGURATION,
        ]);

        // Act
        $securityConfiguration->mergeFirewall(static::FIREWALL_MAIN, [
            static::TEST_FIREWALL_CONFIGURATION_2 => static::TEST_FIREWALL_CONFIGURATION_2,
        ]);
        $securityConfiguration->mergeFirewall(static::FIREWALL_MAIN, [
            static::TEST_FIREWALL_CONFIGURATION_3 => static::TEST_FIREWALL_CONFIGURATION_3,
        ]);

        // Assert
        $firewalls = $securityConfiguration->getConfiguration()->getFirewalls();

        $this->assertArrayHasKey(static::FIREWALL_MAIN, $firewalls);
        $this->assertCount(1, $securityConfiguration->getFirewalls());
        $this->assertSame([
            static::TEST_FIREWALL_CONFIGURATION => static::TEST_FIREWALL_CONFIGURATION,
            static::TEST_FIREWALL_CONFIGURATION_3 => static::TEST_FIREWALL_CONFIGURATION_3,
            static::TEST_FIREWALL_CONFIGURATION_2 => static::TEST_FIREWALL_CONFIGURATION_2,
        ], $firewalls[static::FIREWALL_MAIN]);
    }

    /**
     * @return void
     */
    public function testAddAccessRulesAddsAAccessRule(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addAccessRules(['foo' => 'bar']);
        $securityConfiguration = $securityConfiguration->getConfiguration();

        $this->assertCount(1, $securityConfiguration->getAccessRules());
    }

    /**
     * @return void
     */
    public function testAddRoleHierarchyAddsARoleHierarchy(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addRoleHierarchy(['main' => []]);
        $securityConfiguration = $securityConfiguration->getConfiguration();

        $this->assertCount(1, $securityConfiguration->getRoleHierarchies());
    }

    /**
     * @return void
     */
    public function testAddAuthenticationSuccessHandlerAddsAAuthenticationFailureHandler(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addAuthenticationSuccessHandler(static::FIREWALL_MAIN, function (): void {
        });
        $securityConfiguration = $securityConfiguration->getConfiguration();

        $this->assertCount(1, $securityConfiguration->getAuthenticationSuccessHandlers());
    }

    /**
     * @return void
     */
    public function testAddAuthenticationFailureHandlerAddsAAuthenticationFailureHandler(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addAuthenticationFailureHandler(static::FIREWALL_MAIN, function (): void {
        });
        $securityConfiguration = $securityConfiguration->getConfiguration();

        $this->assertCount(1, $securityConfiguration->getAuthenticationFailureHandlers());
    }

    /**
     * @return void
     */
    public function testAddLogoutHandlerAddsALogoutHandlerBeforeSymfonySecurity51(): void
    {
        if (class_exists(LogoutEvent::class)) {
            $this->markTestSkipped('Adding a logout handler is only allowed in symfony/security-core < 5.1');
        }
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addLogoutHandler(static::FIREWALL_MAIN, function (): void {
        });
        $securityConfiguration = $securityConfiguration->getConfiguration();

        $this->assertCount(1, $securityConfiguration->getLogoutHandlers());
    }

    /**
     * @return void
     */
    public function testAddLogoutHandlerThrowsExceptionWhenSymfonySecurity51IsInstalled(): void
    {
        if (class_exists(LogoutEvent::class)) {
            $this->expectException(SecurityConfigurationException::class);

            $securityConfiguration = new SecurityConfiguration();
            $securityConfiguration->addLogoutHandler(static::FIREWALL_MAIN, function (): void {
            });
        }

        $this->markTestSkipped('Test will only be executed when symfony/security-core <= 5.1 is installed.');
    }

    /**
     * @return void
     */
    public function testAddAccessDeniedHandlerAddsAAccessDeniedHandler(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addAccessDeniedHandler(static::FIREWALL_MAIN, function (): void {
        });
        $securityConfiguration = $securityConfiguration->getConfiguration();

        $this->assertCount(1, $securityConfiguration->getAccessDeniedHandlers());
    }

    /**
     * @return void
     */
    public function testAddEventSubscriberAddsAEventSubscriber(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addEventSubscriber(function (): void {
        });
        $securityConfiguration = $securityConfiguration->getConfiguration();

        $this->assertCount(1, $securityConfiguration->getEventSubscribers());
    }

    /**
     * @return void
     */
    public function testGetConfigurationOnAFrozenConfigurationWillThrowAnException(): void
    {
        $securityConfiguration = new SecurityConfiguration();

        $securityConfiguration->getConfiguration();

        $this->expectException(SecurityConfigurationException::class);
        $securityConfiguration->getConfiguration();
    }

    /**
     * @return void
     */
    public function testUsingAGetterOnANotFrozenConfigurationWillThrowAnException(): void
    {
        $securityConfiguration = new SecurityConfiguration();

        $this->expectException(SecurityConfigurationException::class);
        $securityConfiguration->getFirewalls();
    }

    /**
     * @return void
     */
    public function testMergeFirewallWithComplexNestedArraysPreventArrayToStringConversion(): void
    {
        // Arrange
        $securityConfiguration = new SecurityConfiguration();

        $securityConfiguration->addFirewall(static::FIREWALL_MAIN, [
            'context' => 'agent_merchant_user',
            'switch_user' => [
                'parameter' => '_switch_user',
                'role' => 'ROLE_ALLOWED_TO_SWITCH',
            ],
            'users' => ['user1', 'user2'],
        ]);

        $securityConfiguration->mergeFirewall(static::FIREWALL_MAIN, [
            'context' => 'AgentMerchantUser',
            'switch_user' => [
                'parameter' => '_switch_user',
                'role' => 'ROLE_ALLOWED_TO_SWITCH',
            ],
            'roles' => ['ROLE_USER', 'ROLE_ADMIN'],
        ]);

        // Act
        $configuration = $securityConfiguration->getConfiguration();
        $firewalls = $configuration->getFirewalls();

        // Assert
        $expectedFirewallConfig = [
            'context' => 'AgentMerchantUser',
            'switch_user' => [
                'parameter' => '_switch_user',
                'role' => 'ROLE_ALLOWED_TO_SWITCH',
            ],
            'users' => ['user1', 'user2'],
            'roles' => ['ROLE_USER', 'ROLE_ADMIN'],
        ];

        $this->assertArrayHasKey(static::FIREWALL_MAIN, $firewalls);
        $this->assertSame($expectedFirewallConfig, $firewalls[static::FIREWALL_MAIN]);
    }

    /**
     * @return void
     */
    public function testMergeFirewallReplacesStringValuesInsteadOfMerging(): void
    {
        // Arrange
        $securityConfiguration = new SecurityConfiguration();

        $securityConfiguration->addFirewall(static::FIREWALL_MAIN, [
            'pattern' => '^/(+)merchant-portal-gui',
            'form' => ['login_path' => '/security-gui/login'],
            'logout' => ['logout_path' => '/security-gui/logout'],
        ]);

        $securityConfiguration->mergeFirewall(static::FIREWALL_MAIN, [
            'pattern' => '^/(.+)merchant-portal-gui/',
            'form' => ['login_path' => '/agent-security-gui/login'],
        ]);

        // Act
        $firewalls = $securityConfiguration->getConfiguration()->getFirewalls();

        $expectedConfig = [
            'pattern' => '^/(.+)merchant-portal-gui/',
            'form' => ['login_path' => '/agent-security-gui/login'],
            'logout' => ['logout_path' => '/security-gui/logout'],
        ];

        $this->assertSame($expectedConfig, $firewalls[static::FIREWALL_MAIN]);
    }

    /**
     * @return void
     */
    public function testMultipleMergeFirewallCallsPreventArrayAccumulation(): void
    {
        // Arrange
        $securityConfiguration = new SecurityConfiguration();

        $securityConfiguration->addFirewall(static::FIREWALL_MAIN, [
            'users' => ['initial_user'],
            'context' => 'initial_context',
        ]);

        $securityConfiguration->mergeFirewall(static::FIREWALL_MAIN, [
            'users' => ['first_merge_user'],
            'context' => 'first_merge_context',
        ]);

        $securityConfiguration->mergeFirewall(static::FIREWALL_MAIN, [
            'users' => ['final_user'],
            'roles' => ['ROLE_USER'],
        ]);

        // Act
        $firewalls = $securityConfiguration->getConfiguration()->getFirewalls();

        // Assert
        $expectedConfig = [
            'users' => ['final_user'],
            'context' => 'first_merge_context',
            'roles' => ['ROLE_USER'],
        ];

        $this->assertSame($expectedConfig, $firewalls[static::FIREWALL_MAIN]);
    }

    /**
     * @return void
     */
    public function testMergeFirewallWithMixedArrayTypes(): void
    {
        // Arrange
        $securityConfiguration = new SecurityConfiguration();

        $securityConfiguration->addFirewall(static::FIREWALL_MAIN, [
            'indexed_array' => [0 => '_switch_user', 1 => '_switch_user'],
            'associative_array' => [
                'parameter' => '_switch_user',
                'role' => 'ROLE_ALLOWED_TO_SWITCH',
            ],
        ]);

        $securityConfiguration->mergeFirewall(static::FIREWALL_MAIN, [
            'indexed_array' => [0 => '_switch_user'],
            'associative_array' => [
                'parameter' => '_switch_user',
                'role' => 'ROLE_ALLOWED_TO_SWITCH',
            ],
        ]);

        // Act
        $firewalls = $securityConfiguration->getConfiguration()->getFirewalls();

        // Assert
        $expectedConfig = [
            'indexed_array' => [0 => '_switch_user'],
            'associative_array' => [
                'parameter' => '_switch_user',
                'role' => 'ROLE_ALLOWED_TO_SWITCH',
            ],
        ];

        $this->assertSame($expectedConfig, $firewalls[static::FIREWALL_MAIN]);
    }

    /**
     * @return void
     */
    public function testMergeFirewallWithEmptyArrays(): void
    {
        // Arrange
        $securityConfiguration = new SecurityConfiguration();

        $securityConfiguration->addFirewall(static::FIREWALL_MAIN, [
            'users' => [],
            'roles' => ['ROLE_USER'],
        ]);

        $securityConfiguration->mergeFirewall(static::FIREWALL_MAIN, [
            'users' => ['user1', 'user2'],
            'empty_field' => [],
        ]);

        // Act
        $firewalls = $securityConfiguration->getConfiguration()->getFirewalls();

        // Assert
        $expectedConfig = [
            'users' => ['user1', 'user2'],
            'roles' => ['ROLE_USER'],
            'empty_field' => [],
        ];

        $this->assertSame($expectedConfig, $firewalls[static::FIREWALL_MAIN]);
    }
}
