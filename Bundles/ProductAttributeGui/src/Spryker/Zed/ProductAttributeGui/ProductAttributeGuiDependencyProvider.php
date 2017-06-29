<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToLocaleBridge;
use Spryker\Zed\ProductAttributeGui\Dependency\Service\ProductAttributeGuiToUtilEncodingBridge;

class ProductAttributeGuiDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_LOCALE = 'FACADE_LOCALE';
    const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    const QUERY_CONTAINER_PRODUCT_MANAGEMENT = 'QUERY_CONTAINER_PRODUCT_MANAGEMENT';
    const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->getLocaleFacade($container);

        $container = $this->addProductQueryContainer($container);
        $container = $this->addProductManagementQueryContainer($container);
        $container = $this->addServiceUtilEncoding($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->getLocaleFacade($container);

        $container = $this->addProductQueryContainer($container);
        $container = $this->addProductManagementQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->queryContainer();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductManagementQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_MANAGEMENT] = function (Container $container) {
            return $container->getLocator()->productManagement()->queryContainer();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getLocaleFacade(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ProductAttributeGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addServiceUtilEncoding($container)
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new ProductAttributeGuiToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

}
