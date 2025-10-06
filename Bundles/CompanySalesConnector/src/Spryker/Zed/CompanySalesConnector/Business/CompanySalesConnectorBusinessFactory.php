<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Business;

use Spryker\Zed\CompanySalesConnector\Business\Checker\EditCompanyOrdersPermissionChecker;
use Spryker\Zed\CompanySalesConnector\Business\Checker\EditCompanyOrdersPermissionCheckerInterface;
use Spryker\Zed\CompanySalesConnector\Business\Checker\FilterFieldChecker;
use Spryker\Zed\CompanySalesConnector\Business\Checker\FilterFieldCheckerInterface;
use Spryker\Zed\CompanySalesConnector\Business\Checker\PermissionChecker;
use Spryker\Zed\CompanySalesConnector\Business\Checker\PermissionCheckerInterface;
use Spryker\Zed\CompanySalesConnector\Business\Expander\EditCompanyOrderQuoteExpander;
use Spryker\Zed\CompanySalesConnector\Business\Expander\EditCompanyOrderQuoteExpanderInterface;
use Spryker\Zed\CompanySalesConnector\Business\Expander\OrderSearchQueryExpander;
use Spryker\Zed\CompanySalesConnector\Business\Expander\OrderSearchQueryExpanderInterface;
use Spryker\Zed\CompanySalesConnector\Business\OrderProvider\EditCompanyOrderCartReorderOrderProvider;
use Spryker\Zed\CompanySalesConnector\Business\OrderProvider\EditCompanyOrderCartReorderOrderProviderInterface;
use Spryker\Zed\CompanySalesConnector\Business\Writer\OrderWriter;
use Spryker\Zed\CompanySalesConnector\Business\Writer\OrderWriterInterface;
use Spryker\Zed\CompanySalesConnector\CompanySalesConnectorDependencyProvider;
use Spryker\Zed\CompanySalesConnector\Dependency\Facade\CompanySalesConnectorToCompanyFacadeInterface;
use Spryker\Zed\CompanySalesConnector\Dependency\Facade\CompanySalesConnectorToSalesFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanySalesConnector\Persistence\CompanySalesConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanySalesConnector\CompanySalesConnectorConfig getConfig()
 */
class CompanySalesConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanySalesConnector\Business\Writer\OrderWriterInterface
     */
    public function createOrderWriter(): OrderWriterInterface
    {
        return new OrderWriter(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\CompanySalesConnector\Business\Checker\FilterFieldCheckerInterface
     */
    public function createFilterFieldChecker(): FilterFieldCheckerInterface
    {
        return new FilterFieldChecker();
    }

    /**
     * @return \Spryker\Zed\CompanySalesConnector\Business\Expander\OrderSearchQueryExpanderInterface
     */
    public function createOrderSearchQueryExpander(): OrderSearchQueryExpanderInterface
    {
        return new OrderSearchQueryExpander();
    }

    /**
     * @return \Spryker\Zed\CompanySalesConnector\Business\Checker\PermissionCheckerInterface
     */
    public function createPermissionChecker(): PermissionCheckerInterface
    {
        return new PermissionChecker();
    }

    /**
     * @return \Spryker\Zed\CompanySalesConnector\Business\OrderProvider\EditCompanyOrderCartReorderOrderProviderInterface
     */
    public function createEditCompanyOrderCartReorderOrderProvider(): EditCompanyOrderCartReorderOrderProviderInterface
    {
        return new EditCompanyOrderCartReorderOrderProvider(
            $this->createEditCompanyOrdersPermissionChecker(),
            $this->getSalesFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CompanySalesConnector\Business\Checker\EditCompanyOrdersPermissionCheckerInterface
     */
    public function createEditCompanyOrdersPermissionChecker(): EditCompanyOrdersPermissionCheckerInterface
    {
        return new EditCompanyOrdersPermissionChecker($this->getCompanyFacade());
    }

    /**
     * @return \Spryker\Zed\CompanySalesConnector\Business\Expander\EditCompanyOrderQuoteExpanderInterface
     */
    public function createEditCompanyOrderQuoteExpander(): EditCompanyOrderQuoteExpanderInterface
    {
        return new EditCompanyOrderQuoteExpander(
            $this->createEditCompanyOrdersPermissionChecker(),
            $this->getSalesFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CompanySalesConnector\Dependency\Facade\CompanySalesConnectorToCompanyFacadeInterface
     */
    public function getCompanyFacade(): CompanySalesConnectorToCompanyFacadeInterface
    {
        return $this->getProvidedDependency(CompanySalesConnectorDependencyProvider::FACADE_COMPANY);
    }

    /**
     * @return \Spryker\Zed\CompanySalesConnector\Dependency\Facade\CompanySalesConnectorToSalesFacadeInterface
     */
    public function getSalesFacade(): CompanySalesConnectorToSalesFacadeInterface
    {
        return $this->getProvidedDependency(CompanySalesConnectorDependencyProvider::FACADE_SALES);
    }
}
