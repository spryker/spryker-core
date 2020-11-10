<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Security\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityConfigurationInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin;
use Spryker\Zed\Security\Communication\SecurityCommunicationFactory;
use Spryker\Zed\Security\SecurityConfig;
use Spryker\Zed\Security\SecurityDependencyProvider;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Zed\Application\Helper\ApplicationHelperTrait;
use SprykerTest\Zed\EventDispatcher\Helper\EventDispatcherHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;
use SprykerTest\Zed\Testify\Helper\Communication\CommunicationHelperTrait;
use SprykerTest\Zed\Testify\Helper\Communication\DependencyProviderHelperTrait;

class SecurityHelper extends Module
{
    use DependencyProviderHelperTrait;
    use EventDispatcherHelperTrait;
    use ApplicationHelperTrait;
    use ConfigHelperTrait;
    use CommunicationHelperTrait;
    use BusinessHelperTrait;

    protected const MODULE_NAME = 'Security';

    /**
     * @var array
     */
    protected $securityPlugins = [];

    /**
     * @return void
     */
    public function enableSecurityApplicationPlugin(): void
    {
        $this->getApplicationHelper()->addApplicationPlugin(
            $this->getSecurityApplicationPluginStub()
        );
    }

    /**
     * @param \Spryker\Shared\Security\Configuration\SecurityConfiguration $securityConfiguration
     *
     * @return $this
     */
    public function mockSecurityPlugin(SecurityConfiguration $securityConfiguration)
    {
        $securityPluginStub = Stub::makeEmpty(SecurityPluginInterface::class, [
            'extend' => function (SecurityBuilderInterface $securityBuilder) use ($securityConfiguration) {
                $securityConfiguration = $securityConfiguration->getConfiguration();
                $securityBuilder = $this->addFirewalls($securityBuilder, $securityConfiguration);
                $securityBuilder = $this->addAccessRules($securityBuilder, $securityConfiguration);
                $securityBuilder = $this->addRoleHierarchy($securityBuilder, $securityConfiguration);
                $securityBuilder = $this->addAccessDeniedHandler($securityBuilder, $securityConfiguration);
                $securityBuilder = $this->addAuthenticationSuccessHandler($securityBuilder, $securityConfiguration);
                $securityBuilder = $this->addAuthenticationFailureHandler($securityBuilder, $securityConfiguration);

                return $securityBuilder;
            },
        ]);

        $this->securityPlugins[] = $securityPluginStub;

        $this->getDependencyProviderHelper()->setDependency(SecurityDependencyProvider::PLUGINS_SECURITY, $this->securityPlugins);

        return $this;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface $securityPlugin
     *
     * @return $this
     */
    public function addSecurityPlugin(SecurityPluginInterface $securityPlugin)
    {
        $this->securityPlugins[] = $securityPlugin;
        $this->getDependencyProviderHelper()->setDependency(SecurityDependencyProvider::PLUGINS_SECURITY, $this->securityPlugins);

        return $this;
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->securityPlugins = [];
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin
     */
    protected function getSecurityApplicationPluginStub(): SecurityApplicationPlugin
    {
        /** @var \Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin $securityApplicationPlugin */
        $securityApplicationPlugin = Stub::make(SecurityApplicationPlugin::class, [
            'getConfig' => function () {
                return $this->getConfig();
            },
            'getFactory' => function () {
                return $this->getFactory();
            },
        ]);

        return $securityApplicationPlugin;
    }

    /**
     * @return \Spryker\Zed\Security\SecurityConfig
     */
    protected function getConfig(): SecurityConfig
    {
        /** @var \Spryker\Zed\Security\SecurityConfig $securityConfig */
        $securityConfig = $this->getConfigHelper()->getModuleConfig(static::MODULE_NAME);

        return $securityConfig;
    }

    /**
     * @return \Spryker\Zed\Security\Communication\SecurityCommunicationFactory
     */
    protected function getFactory(): SecurityCommunicationFactory
    {
        /** @var \Spryker\Zed\Security\Communication\SecurityCommunicationFactory $securityFactory */
        $securityFactory = $this->getCommunicationHelper()->getFactory(static::MODULE_NAME);

        return $securityFactory;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityConfigurationInterface $securityConfiguration
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addFirewalls(SecurityBuilderInterface $securityBuilder, SecurityConfigurationInterface $securityConfiguration): SecurityBuilderInterface
    {
        foreach ($securityConfiguration->getFirewalls() as $firewallName => $firewallConfiguration) {
            $securityBuilder->addFirewall($firewallName, $firewallConfiguration);
        }

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityConfigurationInterface $securityConfiguration
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAccessRules(
        SecurityBuilderInterface $securityBuilder,
        SecurityConfigurationInterface $securityConfiguration
    ): SecurityBuilderInterface {
        foreach ($securityConfiguration->getAccessRules() as $accessRules) {
            $securityBuilder->addAccessRules([$accessRules]);
        }

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityConfigurationInterface $securityConfiguration
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addRoleHierarchy(
        SecurityBuilderInterface $securityBuilder,
        SecurityConfigurationInterface $securityConfiguration
    ): SecurityBuilderInterface {
        foreach ($securityConfiguration->getRoleHierarchies() as $mainRole => $roleHierarchy) {
            $roleHierarchy = [$mainRole => $roleHierarchy];
            $securityBuilder->addRoleHierarchy($roleHierarchy);
        }

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityConfigurationInterface $securityConfiguration
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAccessDeniedHandler(
        SecurityBuilderInterface $securityBuilder,
        SecurityConfigurationInterface $securityConfiguration
    ): SecurityBuilderInterface {
        foreach ($securityConfiguration->getAccessDeniedHandlers() as $firewallName => $accessDeniedHandler) {
            $securityBuilder->addAccessDeniedHandler($firewallName, $accessDeniedHandler);
        }

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityConfigurationInterface $securityConfiguration
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAuthenticationSuccessHandler(
        SecurityBuilderInterface $securityBuilder,
        SecurityConfigurationInterface $securityConfiguration
    ): SecurityBuilderInterface {
        foreach ($securityConfiguration->getAuthenticationSuccessHandlers() as $firewallName => $authenticationSuccessHandler) {
            $securityBuilder->addAuthenticationSuccessHandler($firewallName, $authenticationSuccessHandler);
        }

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityConfigurationInterface $securityConfiguration
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAuthenticationFailureHandler(
        SecurityBuilderInterface $securityBuilder,
        SecurityConfigurationInterface $securityConfiguration
    ): SecurityBuilderInterface {
        foreach ($securityConfiguration->getAuthenticationFailureHandlers() as $firewallName => $authenticationFailureHandler) {
            $securityBuilder->addAuthenticationFailureHandler($firewallName, $authenticationFailureHandler);
        }

        return $securityBuilder;
    }
}
