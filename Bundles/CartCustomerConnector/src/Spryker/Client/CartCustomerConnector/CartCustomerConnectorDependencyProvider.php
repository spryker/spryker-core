<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCustomerConnector;

use Spryker\Client\CartCustomerConnector\Dependency\Client\CustomerClientToCartClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CartCustomerConnectorDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_CART = 'CLIENT_CART';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::CLIENT_CART] = function (Container $container) {
            return new CustomerClientToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }

}
