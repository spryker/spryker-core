<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCodesRestApi;

use Spryker\CartCodesRestApi\src\Spryker\Client\CartCodesRestApi\Zed\CartCodesRestApiStubInterface;
use Spryker\Client\CartCodesRestApi\CartCodeAdder\CartCodeAdder;
use Spryker\Client\CartCodesRestApi\CartCodeAdder\CartCodeAdderInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CartCodesRestApiFactory extends AbstractFactory
{
    /**
     * @return CartCodeAdderInterface
     */
    public function createCartCodeAdder(): CartCodeAdderInterface
    {
        return new CartCodeAdder($this->getZedRequestClient());
    }

    /**
     * @return CartCodesRestApiStubInterface
     */
    public function getZedRequestClient(): CartCodesRestApiStubInterface
    {
        return $this->getProvidedDependency(CartCodesRestApiDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
