<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToLocaleBridge;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToPriceFacadeBridge;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductBridge;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductOptionFacadeBridge;

class ProductCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_PRODUCT = 'FACADE_PRODUCT';
    const FACADE_PRODUCT_OPTION = 'FACADE_PRODUCT_OPTION';
    const FACADE_LOCALE = 'FACADE_LOCALE';
    const FACADE_PRICE = 'FACADE_PRICE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductCartConnectorToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new ProductCartConnectorToProductBridge($container->getLocator()->product()->facade());
        };

        $container = $this->addProductOptionFacade($container);
        $container = $this->addPriceFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductOptionFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_OPTION] = function (Container $container) {
            return new ProductCartConnectorToProductOptionFacadeBridge(
                $container->getLocator()->productOption()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceFacade(Container $container)
    {
        $container[static::FACADE_PRICE] = function (Container $container) {
            return new ProductCartConnectorToPriceFacadeBridge(
                $container->getLocator()->price()->facade()
            );
        };

        return $container;
    }
}
