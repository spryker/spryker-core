<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesReclamation\Communication\Table\ReclamationTable;
use Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface;
use Spryker\Zed\SalesReclamation\Dependency\Service\SalesReclamationToUtilDateTimeServiceInterface;
use Spryker\Zed\SalesReclamation\SalesReclamationDependencyProvider;

/**
 * @method \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SalesReclamation\SalesReclamationConfig getConfig()
 */
class SalesReclamationCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesReclamationToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesReclamationDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\SalesReclamation\Communication\Table\ReclamationTable
     */
    public function createReclamationTable(): ReclamationTable
    {
        return new ReclamationTable(
            $this->getQueryContainer(),
            $this->getDateTimeService()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReclamation\Dependency\Service\SalesReclamationToUtilDateTimeServiceInterface
     */
    public function getDateTimeService(): SalesReclamationToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SalesReclamationDependencyProvider::SERVICE_DATETIME);
    }
}
