<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business;

use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker\EditCompanyBusinessUnitOrdersPermissionChecker;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker\EditCompanyBusinessUnitOrdersPermissionCheckerInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker\FilterFieldChecker;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker\FilterFieldCheckerInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker\PermissionChecker;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker\PermissionCheckerInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\EditCompanyBusinessUnitOrderQuoteExpander;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\EditCompanyBusinessUnitOrderQuoteExpanderInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\OrderSearchQueryExpander;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\OrderSearchQueryExpanderInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\OrderProvider\EditBusinessUnitOrderCartReorderOrderProvider;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\OrderProvider\EditBusinessUnitOrderCartReorderOrderProviderInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Writer\OrderWriter;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Writer\OrderWriterInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorDependencyProvider;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToSalesFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorEntityManagerInterface getEntityManager()
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
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker\PermissionCheckerInterface
     */
    public function createPermissionChecker(): PermissionCheckerInterface
    {
        return new PermissionChecker();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\OrderSearchQueryExpanderInterface
     */
    public function createOrderSearchQueryExpander(): OrderSearchQueryExpanderInterface
    {
        return new OrderSearchQueryExpander();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker\FilterFieldCheckerInterface
     */
    public function createFilterFieldChecker(): FilterFieldCheckerInterface
    {
        return new FilterFieldChecker();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\OrderProvider\EditBusinessUnitOrderCartReorderOrderProviderInterface
     */
    public function createEditBusinessUnitOrderCartReorderOrderProvider(): EditBusinessUnitOrderCartReorderOrderProviderInterface
    {
        return new EditBusinessUnitOrderCartReorderOrderProvider(
            $this->createEditCompanyBusinessUnitOrdersPermissionChecker(),
            $this->getSalesFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Checker\EditCompanyBusinessUnitOrdersPermissionCheckerInterface
     */
    public function createEditCompanyBusinessUnitOrdersPermissionChecker(): EditCompanyBusinessUnitOrdersPermissionCheckerInterface
    {
        return new EditCompanyBusinessUnitOrdersPermissionChecker($this->getCompanyBusinessUnitFacade());
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\EditCompanyBusinessUnitOrderQuoteExpanderInterface
     */
    public function createEditCompanyBusinessUnitOrderQuoteExpander(): EditCompanyBusinessUnitOrderQuoteExpanderInterface
    {
        return new EditCompanyBusinessUnitOrderQuoteExpander(
            $this->createEditCompanyBusinessUnitOrdersPermissionChecker(),
            $this->getSalesFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade(): CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitSalesConnectorDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToSalesFacadeInterface
     */
    public function getSalesFacade(): CompanyBusinessUnitSalesConnectorToSalesFacadeInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitSalesConnectorDependencyProvider::FACADE_SALES);
    }
}
