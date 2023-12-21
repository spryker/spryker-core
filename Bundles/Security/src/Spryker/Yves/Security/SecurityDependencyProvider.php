<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Security\Router\SecurityRouter;

/**
 * @method \Spryker\Yves\Security\SecurityConfig getConfig()
 */
class SecurityDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_SECURITY = 'PLUGINS_SECURITY';

    /**
     * @var string
     */
    public const PLUGINS_SECURITY_AUTHENTICATION_LISTENER_FACTORY_TYPE_EXPANDER = 'PLUGINS_SECURITY_AUTHENTICATION_LISTENER_FACTORY_TYPE_EXPANDER';

    /**
     * @var string
     */
    public const SERVICE_SECURITY_ROUTERS = 'SECURITY_ROUTERS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addSecurityPlugins($container);
        $container = $this->addSecurityAuthenticationListenerFactoryTypeExpanderPlugins($container);
        $container = $this->addSecurityRouter($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSecurityPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SECURITY, function () {
            return $this->getSecurityPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface>
     */
    protected function getSecurityPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSecurityAuthenticationListenerFactoryTypeExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SECURITY_AUTHENTICATION_LISTENER_FACTORY_TYPE_EXPANDER, function () {
            return $this->getSecurityAuthenticationListenerFactoryTypeExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityAuthenticationListenerFactoryTypeExpanderPluginInterface>
     */
    protected function getSecurityAuthenticationListenerFactoryTypeExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSecurityRouter(Container $container): Container
    {
        $container->set(static::SERVICE_SECURITY_ROUTERS, function () {
            return new SecurityRouter();
        });

        return $container;
    }
}
