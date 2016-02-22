<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionBridge;

class ProductOptionCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_PRODUCT_OPTION = 'FACADE_PRODUCT_OPTION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_PRODUCT_OPTION] = function (Container $container) {
            return new ProductOptionCartConnectorToProductOptionBridge($container->getLocator()->productOption()->facade());
        };

        return $container;
    }

}
