<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyBusinessUnitSalesConnector;

use Spryker\Client\CompanyBusinessUnitSalesConnector\Dependency\Client\CompanyBusinessUnitSalesConnectorToZedRequestClientInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CompanyBusinessUnitSalesConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CompanyBusinessUnitSalesConnector\Dependency\Client\CompanyBusinessUnitSalesConnectorToZedRequestClientInterface
     */
    public function getZedRequestClient(): CompanyBusinessUnitSalesConnectorToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitSalesConnectorDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
