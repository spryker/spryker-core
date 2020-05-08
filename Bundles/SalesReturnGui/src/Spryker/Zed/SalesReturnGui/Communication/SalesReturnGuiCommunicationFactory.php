<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesReturnGui\Communication\Extractor\ReturnExtractor;
use Spryker\Zed\SalesReturnGui\Communication\Extractor\ReturnExtractorInterface;
use Spryker\Zed\SalesReturnGui\Communication\Table\OrderReturnTable;
use Spryker\Zed\SalesReturnGui\Communication\Table\ReturnTable;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToCustomerFacadeInterface;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToMoneyFacadeInterface;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToOmsFacadeInterface;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface;
use Spryker\Zed\SalesReturnGui\Dependency\Service\SalesReturnGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\SalesReturnGui\SalesReturnGuiDependencyProvider;

/**
 * @method \Spryker\Zed\SalesReturnGui\SalesReturnGuiConfig getConfig()
 */
class SalesReturnGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SalesReturnGui\Communication\Table\ReturnTable
     */
    public function createReturnTable(): ReturnTable
    {
        return new ReturnTable(
            $this->getUtilDateTimeService(),
            $this->getSalesReturnPropelQuery()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Zed\SalesReturnGui\Communication\Table\OrderReturnTable
     */
    public function createOrderReturnTable(OrderTransfer $orderTransfer): OrderReturnTable
    {
        return new OrderReturnTable(
            $orderTransfer,
            $this->getMoneyFacade(),
            $this->getSalesReturnPropelQuery()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Communication\Extractor\ReturnExtractorInterface
     */
    public function createReturnExtractor(): ReturnExtractorInterface
    {
        return new ReturnExtractor();
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): SalesReturnGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface
     */
    public function getSalesReturnFacade(): SalesReturnGuiToSalesReturnFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnGuiDependencyProvider::FACADE_SALES_RETURN);
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToCustomerFacadeInterface
     */
    public function getCustomerFacade(): SalesReturnGuiToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnGuiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToOmsFacadeInterface
     */
    public function getOmsFacade(): SalesReturnGuiToOmsFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnGuiDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Dependency\Service\SalesReturnGuiToUtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): SalesReturnGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SalesReturnGuiDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery
     */
    public function getSalesReturnPropelQuery(): SpySalesReturnQuery
    {
        return $this->getProvidedDependency(SalesReturnGuiDependencyProvider::PROPEL_QUERY_SALES_RETURN);
    }
}
