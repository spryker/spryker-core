<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Sales;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Sales\Zed\SalesStub;

/**
 * @method \Spryker\Client\Sales\SalesConfig getConfig()
 */
class SalesFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Sales\Zed\SalesStubInterface
     */
    public function createZedSalesStub()
    {
        return new SalesStub(
            $this->getProvidedDependency(SalesDependencyProvider::SERVICE_ZED)
        );
    }
}
