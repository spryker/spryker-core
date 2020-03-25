<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business;

use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker\FilterFieldChecker;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker\FilterFieldCheckerInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\OrderSearchQueryExpander;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\OrderSearchQueryExpanderInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Reader\CompanyBusinessUnitReader;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Reader\CompanyBusinessUnitReaderInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Writer\OrderWriter;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Writer\OrderWriterInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorDependencyProvider;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToCompanySalesConnectorFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorConfig getConfig()
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
            $this->getCompanyBusinessUnitFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker\FilterFieldCheckerInterface
     */
    public function createFilterFieldChecker(): FilterFieldCheckerInterface
    {
        return new FilterFieldChecker(
            $this->getCompanySalesConnectorFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\OrderSearchQueryExpanderInterface
     */
    public function createOrderSearchQueryExpander(): OrderSearchQueryExpanderInterface
    {
        return new OrderSearchQueryExpander();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade(): CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitSalesConnectorDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToCompanySalesConnectorFacadeInterface
     */
    public function getCompanySalesConnectorFacade(): CompanyBusinessUnitSalesConnectorToCompanySalesConnectorFacadeInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitSalesConnectorDependencyProvider::FACADE_COMPANY_SALES_CONNECTOR);
    }
}
