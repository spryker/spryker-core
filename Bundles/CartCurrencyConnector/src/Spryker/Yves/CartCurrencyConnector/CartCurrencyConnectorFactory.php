<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CartCurrencyConnector;

use Spryker\Yves\Kernel\AbstractFactory;

class CartCurrencyConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\CartCurrencyConnector\Dependency\Client\CartCurrencyConnectorToCartClientInterface
     */
    public function getCartClient()
    {
        return $this->getProvidedDependency(CartCurrencyConnectorDependencyProvider::CLIENT_CART);
    }
}
