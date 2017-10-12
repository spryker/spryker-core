<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CartCurrencyConnector;

use Spryker\Yves\CartCurrencyConnector\Dependency\Client\CartCurrencyConnectorToCartClientBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class CartCurrencyConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_CART = 'CLIENT_CART';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCartClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient(Container $container)
    {
        $container[static::CLIENT_CART] = function (Container $container) {
            return new CartCurrencyConnectorToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }
}
