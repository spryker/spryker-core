<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeBridge;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductFacadeBridge;

/**
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 */
class ProductBundleProductListConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_PRODUCT_BUNDLE = 'FACADE_PRODUCT_BUNDLE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addFacadeProduct($container);
        $container = $this->addFacadeProductBundle($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeProduct(Container $container): Container
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ProductBundleProductListConnectorToProductFacadeBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeProductBundle(Container $container): Container
    {
        $container[static::FACADE_PRODUCT_BUNDLE] = function (Container $container) {
            return new ProductBundleProductListConnectorToProductBundleFacadeBridge($container->getLocator()->productBundle()->facade());
        };

        return $container;
    }
}
