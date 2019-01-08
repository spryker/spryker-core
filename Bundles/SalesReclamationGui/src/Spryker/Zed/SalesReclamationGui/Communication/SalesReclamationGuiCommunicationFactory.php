<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Communication;

use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesReclamationGui\Communication\ReclamationItem\ReclamationItemEventsFinder;
use Spryker\Zed\SalesReclamationGui\Communication\ReclamationItem\ReclamationItemEventsFinderInterface;
use Spryker\Zed\SalesReclamationGui\Communication\Table\ReclamationTable;
use Spryker\Zed\SalesReclamationGui\Dependency\Facade\SalesReclamationGuiToOmsFacadeInterface;
use Spryker\Zed\SalesReclamationGui\Dependency\Facade\SalesReclamationGuiToSalesFacadeInterface;
use Spryker\Zed\SalesReclamationGui\Dependency\Facade\SalesReclamationGuiToSalesReclamationFacadeInterface;
use Spryker\Zed\SalesReclamationGui\Dependency\Service\SalesReclamationGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\SalesReclamationGui\SalesReclamationGuiDependencyProvider;

/**
 * @method \Spryker\Zed\SalesReclamationGui\SalesReclamationGuiConfig getConfig()
 */
class SalesReclamationGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SalesReclamationGui\Dependency\Facade\SalesReclamationGuiToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesReclamationGuiToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesReclamationGuiDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\SalesReclamationGui\Dependency\Facade\SalesReclamationGuiToSalesReclamationFacadeInterface
     */
    public function getSalesReclamationFacade(): SalesReclamationGuiToSalesReclamationFacadeInterface
    {
        return $this->getProvidedDependency(SalesReclamationGuiDependencyProvider::FACADE_SALES_RECLAMATION);
    }

    /**
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationQuery
     */
    public function getSalesReclamationPropelQuery(): SpySalesReclamationQuery
    {
        return $this->getProvidedDependency(SalesReclamationGuiDependencyProvider::PROPEL_QUERY_SALES_RECLAMATION);
    }

    /**
     * @return \Spryker\Zed\SalesReclamationGui\Communication\Table\ReclamationTable
     */
    public function createReclamationTable(): ReclamationTable
    {
        return new ReclamationTable(
            $this->getSalesReclamationPropelQuery(),
            $this->getDateTimeService()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReclamationGui\Dependency\Service\SalesReclamationGuiToUtilDateTimeServiceInterface
     */
    public function getDateTimeService(): SalesReclamationGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SalesReclamationGuiDependencyProvider::SERVICE_DATETIME);
    }

    /**
     * @return \Spryker\Zed\SalesReclamationGui\Dependency\Facade\SalesReclamationGuiToOmsFacadeInterface
     */
    public function getOmsFacade(): SalesReclamationGuiToOmsFacadeInterface
    {
        return $this->getProvidedDependency(SalesReclamationGuiDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\SalesReclamationGui\Communication\ReclamationItem\ReclamationItemEventsFinderInterface
     */
    public function createReclamationItemEventsFinder(): ReclamationItemEventsFinderInterface
    {
        return new ReclamationItemEventsFinder();
    }
}
