<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CheckoutRestApi;

use Spryker\Client\CheckoutRestApi\Dependency\Client\CheckoutRestApiToQuoteClientInterface;
use Spryker\Client\CheckoutRestApi\Dependency\Client\CheckoutRestApiToZedRequestClientInterface;
use Spryker\Client\CheckoutRestApi\Zed\CheckoutRestApiStub;
use Spryker\Client\CheckoutRestApi\Zed\CheckoutRestApiStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CheckoutRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CheckoutRestApi\Zed\CheckoutRestApiStubInterface
     */
    public function createZedStub(): CheckoutRestApiStubInterface
    {
        return new CheckoutRestApiStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\CheckoutRestApi\Dependency\Client\CheckoutRestApiToZedRequestClientInterface
     */
    public function getZedRequestClient(): CheckoutRestApiToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\CheckoutRestApi\Dependency\Client\CheckoutRestApiToQuoteClientInterface
     */
    public function getQuoteClient(): CheckoutRestApiToQuoteClientInterface
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::CLIENT_QUOTE);
    }
}
