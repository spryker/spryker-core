<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartsRestApi;

use Spryker\Client\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface;
use Spryker\Client\CartsRestApi\Zed\CartsRestApiZedStub;
use Spryker\Client\CartsRestApi\Zed\CartsRestApiZedStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CartsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CartsRestApi\Zed\CartsRestApiZedStubInterface
     */
    public function createCartsRestApiZedStub(): CartsRestApiZedStubInterface
    {
        return new CartsRestApiZedStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface
     */
    public function getZedRequestClient(): CartsRestApiToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
