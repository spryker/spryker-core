<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Sales\Service;

use SprykerFeature\Client\Sales\Service\Zed\SalesStub;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Sales\Service\Zed\SalesStubInterface;
use SprykerFeature\Client\Sales\SalesDependencyProvider;

class SalesDependencyContainer extends AbstractServiceDependencyContainer
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
