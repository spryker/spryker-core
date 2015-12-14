<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Sales;

use SprykerFeature\Client\Sales\Zed\SalesStub;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Sales\Zed\SalesStubInterface;
use SprykerFeature\Client\Sales\SalesDependencyProvider;

class SalesDependencyContainer extends AbstractDependencyContainer
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
