<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyBusinessUnitSalesConnector;

use Spryker\Client\CompanyBusinessUnitSalesConnector\Dependency\Client\CompanyBusinessUnitSalesConnectorToZedRequestClientInterface;
use Spryker\Client\CompanyBusinessUnitSalesConnector\Zed\CompanyBusinessUnitSalesConnectorStub;
use Spryker\Client\CompanyBusinessUnitSalesConnector\Zed\CompanyBusinessUnitSalesConnectorStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CompanyBusinessUnitSalesConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CompanyBusinessUnitSalesConnector\Zed\CompanyBusinessUnitSalesConnectorStubInterface
     */
    public function createZedCompanyBusinessUnitSalesConnectorStub(): CompanyBusinessUnitSalesConnectorStubInterface
    {
        return new CompanyBusinessUnitSalesConnectorStub(
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\CompanyBusinessUnitSalesConnector\Dependency\Client\CompanyBusinessUnitSalesConnectorToZedRequestClientInterface
     */
    public function getZedRequestClient(): CompanyBusinessUnitSalesConnectorToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitSalesConnectorDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
