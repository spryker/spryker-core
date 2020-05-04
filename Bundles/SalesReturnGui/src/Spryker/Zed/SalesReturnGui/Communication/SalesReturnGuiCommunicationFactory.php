<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication;

use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesReturnGui\Communication\Table\ReturnTable;
use Spryker\Zed\SalesReturnGui\Dependency\Service\SalesReturnGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\SalesReturnGui\SalesReturnGuiDependencyProvider;

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
