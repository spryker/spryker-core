<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Business;

use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesStatistics\Business\Reader\Reader;
use Spryker\Zed\SalesStatistics\SalesStatisticsDependencyProvider;

/**
 * @method \Spryker\Zed\SalesStatistics\Persistence\SalesStatisticsRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesStatistics\SalesStatisticsConfig getConfig()
 */
class SalesStatisticsBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function getSpySalesOrderQuery(): SpySalesOrderQuery
    {
        return $this->getProvidedDependency(SalesStatisticsDependencyProvider::PROPEL_SALES_ORDER_QUERY);
    }

    /**
     * @return \Spryker\Zed\SalesStatistics\Business\Reader\ReaderInterface
     */
    public function createReader()
    {
        return new Reader($this->getRepository());
    }
}
