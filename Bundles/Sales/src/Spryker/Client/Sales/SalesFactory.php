<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Sales;

use Spryker\Client\Sales\Zed\SalesStub;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Sales\Zed\SalesStubInterface;
use Spryker\Client\Sales\SalesDependencyProvider;

class SalesFactory extends AbstractFactory
{

    /**
     * @return SalesStubInterface
     */
    public function createZedSalesStub()
    {
        return new SalesStub(
            $this->getProvidedDependency(SalesDependencyProvider::SERVICE_ZED)
        );
    }

}
