<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business;

use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Reader\CompanyBusinessUnitReader;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Reader\CompanyBusinessUnitReaderInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Writer\OrderWriter;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Writer\OrderWriterInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorDependencyProvider;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Client\CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorRepositoryInterface getRepository()
 */
class CompanyBusinessUnitSalesConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Writer\OrderWriterInterface
     */
    public function createOrderWriter(): OrderWriterInterface
    {
        return new OrderWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Reader\CompanyBusinessUnitReaderInterface
     */
    public function createCompanyBusinessUnitReader(): CompanyBusinessUnitReaderInterface
    {
        return new CompanyBusinessUnitReader(
            $this->getCompanyBusinessUnitClient()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Client\CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitClientInterface
     */
    public function getCompanyBusinessUnitClient(): CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitClientInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitSalesConnectorDependencyProvider::CLIENT_COMPANY_BUSINESS_UNIT);
    }
}
