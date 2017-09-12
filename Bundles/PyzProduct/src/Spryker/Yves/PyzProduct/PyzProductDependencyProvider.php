<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PyzProduct;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class PyzProductDependencyProvider extends AbstractBundleDependencyProvider
{

    const CLIENT_PRODUCT_GROUP = 'CLIENT_PRODUCT_GROUP';
    const PLUGIN_CONTROLLER_RESPONSE_EXPANDERS = 'PLUGIN_CONTROLLER_RESPONSE_EXPANDERS';
    const PLUGIN_STORAGE_PRODUCT_EXPANDERS = 'PLUGIN_STORAGE_PRODUCT_EXPANDERS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductGroupClient($container);
        $container = $this->addControllerResponseExpanderPlugins($container);
        $container = $this->addStorageProductExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductGroupClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_GROUP] = function (Container $container) {
            return $container->getLocator()->productGroup()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addControllerResponseExpanderPlugins(Container $container)
    {
        $container[self::PLUGIN_CONTROLLER_RESPONSE_EXPANDERS] = function (Container $container) {
            return $this->getControllerResponseExpanderPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Dependency\Plugin\ControllerResponseExpanderPluginInterface[]
     */
    protected function getControllerResponseExpanderPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStorageProductExpanderPlugins(Container $container)
    {
        $container[self::PLUGIN_STORAGE_PRODUCT_EXPANDERS] = function (Container $container) {
            return $this->getStorageProductExpanderPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\PyzProduct\Dependency\Plugin\StorageProductExpanderPluginInterface[]
     */
    protected function getStorageProductExpanderPlugins(Container $container)
    {
        return [];
    }

}
