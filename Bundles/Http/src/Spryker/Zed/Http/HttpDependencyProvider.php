<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Http;

use Spryker\Zed\Http\Dependency\Facade\HttpToLocaleFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Http\HttpConfig getConfig()
 */
class HttpDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const PLUGINS_FRAGMENT_HANDLER = 'PLUGINS_FRAGMENT_HANDLER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addLocaleFacade($container);
        $container = $this->addFragmentHandlerPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new HttpToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFragmentHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_FRAGMENT_HANDLER, function () {
            return $this->getFragmentHandlerPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\HttpExtension\Dependency\Plugin\FragmentHandlerPluginInterface[]
     */
    protected function getFragmentHandlerPlugins(): array
    {
        return [];
    }
}
