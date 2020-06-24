<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthMailConnector;

use Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig getConfig()
 */
class AuthMailConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MAIL = 'mail facade';
    public const PLUGINS_AUTH_MAIL_EXPANDER = 'PLUGINS_AUTH_MAIL_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addMailFacade($container);
        $container = $this->addAuthMailExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMailFacade(Container $container): Container
    {
        $container->set(static::FACADE_MAIL, function (Container $container) {
            return new AuthMailConnectorToMailBridge($container->getLocator()->mail()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAuthMailExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_AUTH_MAIL_EXPANDER, function () {
            return $this->getAuthMailExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\AuthMailConnectorExtension\Dependency\Plugin\AuthMailExpanderPluginInterface[]
     */
    protected function getAuthMailExpanderPlugins(): array
    {
        return [];
    }
}
