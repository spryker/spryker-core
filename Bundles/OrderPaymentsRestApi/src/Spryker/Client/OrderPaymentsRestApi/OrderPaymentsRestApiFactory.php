<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OrderPaymentsRestApi;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\OrderPaymentsRestApi\Dependency\Client\OrderPaymentsRestApiToZedRequestClientInterface;
use Spryker\Client\OrderPaymentsRestApi\Zed\OrderPaymentsRestApiZedStub;
use Spryker\Client\OrderPaymentsRestApi\Zed\OrderPaymentsRestApiZedStubInterface;

class OrderPaymentsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\OrderPaymentsRestApi\Zed\OrderPaymentsRestApiZedStubInterface
     */
    public function createOrderPaymentsRestApiZedStub(): OrderPaymentsRestApiZedStubInterface
    {
        return new OrderPaymentsRestApiZedStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\OrderPaymentsRestApi\Dependency\Client\OrderPaymentsRestApiToZedRequestClientInterface
     */
    public function getZedRequestClient(): OrderPaymentsRestApiToZedRequestClientInterface
    {
        return $this->getProvidedDependency(OrderPaymentsRestApiDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
