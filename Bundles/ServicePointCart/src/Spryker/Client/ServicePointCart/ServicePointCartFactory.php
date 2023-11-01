<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointCart;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToZedRequestClientInterface;
use Spryker\Client\ServicePointCart\Zed\ServicePointCartStub;
use Spryker\Client\ServicePointCart\Zed\ServicePointCartStubInterface;

class ServicePointCartFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ServicePointCart\Zed\ServicePointCartStubInterface
     */
    public function createServicePointCartStub(): ServicePointCartStubInterface
    {
        return new ServicePointCartStub(
            $this->getZedRequestClient(),
        );
    }

    /**
     * @return \Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToZedRequestClientInterface
     */
    public function getZedRequestClient(): ServicePointCartToZedRequestClientInterface
    {
        return $this->getProvidedDependency(ServicePointCartDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
