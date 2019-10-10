<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCodesRestApi;

use Spryker\CartCodesRestApi\src\Spryker\Client\CartCodesRestApi\Zed\CartCodesRestApiStub;
use Spryker\CartCodesRestApi\src\Spryker\Client\CartCodesRestApi\Zed\CartCodesRestApiStubInterface;
use Spryker\Client\CartCodesRestApi\CartCodeAdder\CartCodeAdder;
use Spryker\Client\CartCodesRestApi\CartCodeAdder\CartCodeAdderInterface;
use Spryker\Client\CartCodesRestApi\Dependency\Client\CartCodesRestApiToZedRequestClientInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CartCodesRestApiFactory extends AbstractFactory
{
    /**
     * @return CartCodeAdderInterface
     */
    public function createCartCodeAdder(): CartCodeAdderInterface
    {
        return new CartCodeAdder($this->createCartCodesRestApiStub());
    }

    public function createCartCodesRestApiStub(): CartCodesRestApiStubInterface
    {
        return new CartCodesRestApiStub($this->getZedRequestClient());
    }

    /**
     * @return CartCodesRestApiToZedRequestClientInterface
     */
    public function getZedRequestClient(): CartCodesRestApiToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CartCodesRestApiDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
