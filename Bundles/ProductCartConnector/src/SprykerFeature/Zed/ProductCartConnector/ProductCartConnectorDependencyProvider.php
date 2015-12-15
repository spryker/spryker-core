<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCartConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ProductCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_PRODUCT = 'product_facade';

    const FACADE_PRODUCT_CART_CONNECTOR = 'FACADE_PRODUCT_OPTION_CART_CONNECTOR';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->facade();
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_PRODUCT_CART_CONNECTOR] = function (Container $container) {
            return $container->getLocator()->productCartConnector()->facade();
        };

        return $container;
    }

}
