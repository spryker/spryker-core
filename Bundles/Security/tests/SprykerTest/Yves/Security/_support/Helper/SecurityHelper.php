<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Security\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Spryker\Shared\Security\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Yves\Security\Configuration\SecurityBuilderInterface;
use Spryker\Yves\Security\Configuration\SecurityConfiguration;
use Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin;
use Spryker\Yves\Security\Plugin\Security\RememberMeSecurityPlugin;
use Spryker\Yves\Security\SecurityConfig;
use Spryker\Yves\Security\SecurityDependencyProvider;
use Spryker\Yves\Security\SecurityFactory;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;
use SprykerTest\Shared\Testify\Helper\DependencyHelper;
use SprykerTest\Yves\Testify\Helper\ApplicationHelper;
use SprykerTest\Yves\Testify\Helper\FactoryHelper;

class SecurityHelper extends Module
{
    /**
     * @var array
     */
    protected $securityPlugins = [];

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->securityPlugins[] = new RememberMeSecurityPlugin();

        $this->getApplicationHelper()->addApplicationPlugin(
            $this->getSecurityApplicationPluginStub()
        );
    }

    /**
     * @return \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin
     */
    protected function getSecurityApplicationPluginStub()
    {
        /** @var \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin $securityApplicationPlugin */
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
     * @return \Spryker\Yves\Security\SecurityConfig
     */
    protected function getConfig(): SecurityConfig
    {
        /** @var \Spryker\Yves\Security\SecurityConfig $securityConfig */
        $securityConfig = $this->getConfigHelper()->getModuleConfig();

        return $securityConfig;
    }

    /**
     * @return \SprykerTest\Shared\Testify\Helper\ConfigHelper
     */
    protected function getConfigHelper(): ConfigHelper
    {
        /** @var \SprykerTest\Shared\Testify\Helper\ConfigHelper $configHelper */
        $configHelper = $this->getModule('\\' . ConfigHelper::class);

        return $configHelper;
    }

    /**
     * @return \Spryker\Yves\Security\SecurityFactory
     */
    protected function getFactory(): SecurityFactory
    {
        /** @var \Spryker\Yves\Security\SecurityFactory $securityFactory */
        $securityFactory = $this->getFactoryHelper()->getFactory();

        return $securityFactory;
    }

    /**
     * @return \SprykerTest\Yves\Testify\Helper\FactoryHelper
     */
    protected function getFactoryHelper(): FactoryHelper
    {
        /** @var \SprykerTest\Yves\Testify\Helper\FactoryHelper $factoryHelper */
        $factoryHelper = $this->getModule('\\' . FactoryHelper::class);

        return $factoryHelper;
    }

    /**
     * @return \SprykerTest\Yves\Testify\Helper\ApplicationHelper
     */
    protected function getApplicationHelper(): ApplicationHelper
    {
        /** @var \SprykerTest\Yves\Testify\Helper\ApplicationHelper $applicationHelper */
        $applicationHelper = $this->getModule('\\' . ApplicationHelper::class);

        return $applicationHelper;
    }

    /**
     * @param \Spryker\Yves\Security\Configuration\SecurityConfiguration $securityConfiguration
     *
     * @return $this
     */
    public function addSecurityPlugin(SecurityConfiguration $securityConfiguration)
    {
        $securityPluginStub = Stub::makeEmpty(SecurityPluginInterface::class, [
            'extend' => function (SecurityBuilderInterface $securityBuilder) use ($securityConfiguration) {
                foreach ($securityConfiguration->getFirewalls() as $firewallName => $firewallConfiguration) {
                    $securityBuilder->addFirewall($firewallName, $firewallConfiguration);
                }
                foreach ($securityConfiguration->getAccessRules() as $accessRules) {
                    $securityBuilder->addAccessRules($accessRules);
                }
                foreach ($securityConfiguration->getRoleHierarchies() as $mainRole => $roleHierarchy) {
                    $roleHierarchy = [$mainRole => $roleHierarchy];
                    $securityBuilder->addRoleHierarchy($roleHierarchy);
                }
                foreach ($securityConfiguration->getAccessDeniedHandler() as $firewallName => $accessDeniedHandler) {
                    $securityBuilder->addAccessDeniedHandler($firewallName, $accessDeniedHandler);
                }
                foreach ($securityConfiguration->getAuthenticationSuccessHandler() as $firewallName => $authenticationSuccessHandler) {
                    $securityBuilder->addAuthenticationSuccessHandler($firewallName, $authenticationSuccessHandler);
                }
                foreach ($securityConfiguration->getAuthenticationFailureHandler() as $firewallName => $authenticationFailureHandler) {
                    $securityBuilder->addAuthenticationFailureHandler($firewallName, $authenticationFailureHandler);
                }

                return $securityBuilder;
            },
        ]);

        $this->securityPlugins[] = $securityPluginStub;

        $this->getDependencyHelper()->setDependency(SecurityDependencyProvider::PLUGINS_SECURITY, $this->securityPlugins);

        return $this;
    }

    /**
     * @return \SprykerTest\Shared\Testify\Helper\DependencyHelper
     */
    protected function getDependencyHelper(): DependencyHelper
    {
        /** @var \SprykerTest\Shared\Testify\Helper\DependencyHelper $dependencyHelper */
        $dependencyHelper = $this->getModule('\\' . DependencyHelper::class);

        return $dependencyHelper;
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
}
