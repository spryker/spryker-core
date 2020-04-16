<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesReturn;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SalesReturn\Dependency\Client\SalesReturnToZedRequestClientInterface;
use Spryker\Client\SalesReturn\Zed\SalesReturnStub;
use Spryker\Client\SalesReturn\Zed\SalesReturnStubInterface;

/**
 * @method \Spryker\Client\SalesReturn\SalesReturnConfig getConfig()
 */
class SalesReturnFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SalesReturn\Zed\SalesReturnStubInterface
     */
    public function createSalesReturnStub(): SalesReturnStubInterface
    {
        return new SalesReturnStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\SalesReturn\Dependency\Client\SalesReturnToZedRequestClientInterface
     */
    public function getZedRequestClient(): SalesReturnToZedRequestClientInterface
    {
        return $this->getProvidedDependency(SalesReturnDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
