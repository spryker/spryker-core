<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCurrencyConnector;

use Spryker\Client\CartCurrencyConnector\Dependency\Client\CartCurrencyConnectorToCartClientInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CartCurrencyConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CartCurrencyConnector\Dependency\Client\CartCurrencyConnectorToCartClientInterface
     */
    public function getCartClient(): CartCurrencyConnectorToCartClientInterface
    {
        return $this->getProvidedDependency(CartCurrencyConnectorDependencyProvider::CLIENT_CART);
    }
}
