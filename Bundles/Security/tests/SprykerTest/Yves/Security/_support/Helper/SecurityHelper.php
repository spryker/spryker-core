<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Security\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityConfigurationInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Yves\Router\Plugin\EventDispatcher\RouterListenerEventDispatcherPlugin;
use Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin;
use Spryker\Yves\Security\Plugin\Application\YvesSecurityApplicationPlugin;
use Spryker\Yves\Security\Plugin\Security\RememberMeSecurityPlugin;
use Spryker\Yves\Security\Router\SecurityRouter;
use Spryker\Yves\Security\SecurityConfig;
use Spryker\Yves\Security\SecurityDependencyProvider;
use Spryker\Yves\Security\SecurityFactory;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Yves\Application\Helper\ApplicationHelperTrait;
use SprykerTest\Yves\EventDispatcher\Helper\EventDispatcherHelperTrait;
use SprykerTest\Yves\Testify\Helper\DependencyProviderHelperTrait;
use SprykerTest\Yves\Testify\Helper\FactoryHelperTrait;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;

class SecurityHelper extends Module
{
    use ConfigHelperTrait;
    use FactoryHelperTrait;
    use ApplicationHelperTrait;
    use EventDispatcherHelperTrait;
    use DependencyProviderHelperTrait;

    /**
     * @var string
     */
    protected const MODULE_NAME = 'Security';

    /**
     * @var string
     */
    protected const REQUEST_STACK = 'request_stack';

    /**
     * @var string
     */
    protected const REMEMBER_ME = 'remember_me';

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
        if ($this->isSymfonyVersion5() === true) {
            $this->securityPlugins[] = new RememberMeSecurityPlugin();

            $this->getApplicationHelper()->addApplicationPlugin(
                $this->getSecurityApplicationPluginStub(),
            );
        }

        $this->getEventDispatcherHelper()->addEventDispatcherPlugin(new RouterListenerEventDispatcherPlugin());
    }

    /**
     * @return \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin
     */
    protected function getSecurityApplicationPluginStub()
    {
        $securityApplicationPlugin = $this->isSymfonyVersion5() === true ? SecurityApplicationPlugin::class : YvesSecurityApplicationPlugin::class;

        $securityApplicationPlugin = Stub::make($securityApplicationPlugin, [
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
        $securityConfig = $this->getConfigHelper()->getModuleConfig(static::MODULE_NAME);

        return $securityConfig;
    }

    /**
     * @return \Spryker\Yves\Security\SecurityFactory
     */
    protected function getFactory(): SecurityFactory
    {
        /** @var \Spryker\Yves\Security\SecurityFactory $securityFactory */
        $securityFactory = $this->getFactoryHelper()->getFactory(static::MODULE_NAME);

        return $securityFactory;
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
     * @param \Spryker\Shared\Security\Configuration\SecurityConfiguration $securityConfiguration
     *
     * @return $this
     */
    public function mockYvesSecurityPlugin(SecurityConfiguration $securityConfiguration)
    {
        $securityPluginStub = Stub::makeEmpty(SecurityPluginInterface::class, [
            'extend' => function (SecurityBuilderInterface $securityBuilder) use ($securityConfiguration) {
                $securityConfiguration = $securityConfiguration->getConfiguration();
                $securityBuilder = $this->addFirewalls($securityBuilder, $securityConfiguration);
                $securityBuilder = $this->addAccessRules($securityBuilder, $securityConfiguration);
                $securityBuilder = $this->addRoleHierarchy($securityBuilder, $securityConfiguration);

                return $securityBuilder;
            },
        ]);

        $this->securityPlugins[] = $securityPluginStub;

        $this->getDependencyProviderHelper()->setDependency(SecurityDependencyProvider::PLUGINS_SECURITY, $this->securityPlugins);

        return $this;
    }

    /**
     * @return void
     */
    public function enableSecurityApplicationPlugin(): void
    {
        $this->getApplicationHelper()->addApplicationPlugin(
            $this->getSecurityApplicationPluginStub(),
        );
    }

    /**
     * @return void
     */
    public function mockSecurityDependencies(): void
    {
        $this->getDependencyProviderHelper()->setDependency(
            SecurityDependencyProvider::SERVICE_SECURITY_ROUTERS,
            new SecurityRouter(),
        );
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
     * @deprecated Shim for Symfony Security Core 5.x, to be removed when Symfony Security Core dependency becomes 6.x+.
     *
     * @return bool
     */
    public function isSymfonyVersion5(): bool
    {
        return class_exists(AuthenticationProviderManager::class);
    }
}
