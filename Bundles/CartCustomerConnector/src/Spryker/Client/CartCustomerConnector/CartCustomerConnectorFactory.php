<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCustomerConnector;

use Spryker\Client\CartCustomerConnector\Dependency\Client\CustomerClientToCartClientInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CartCustomerConnectorFactory extends AbstractFactory
{
    /**
     * @return CustomerClientToCartClientInterface
     */
    public function getCartClient()
    {
        return $this->getProvidedDependency(CartCustomerConnectorDependencyProvider::CLIENT_CART);
    }
}
