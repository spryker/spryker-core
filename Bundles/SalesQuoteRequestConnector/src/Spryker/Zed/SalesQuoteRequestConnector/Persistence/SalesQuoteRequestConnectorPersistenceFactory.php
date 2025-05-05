<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuoteRequestConnector\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesQuoteRequestConnector\SalesQuoteRequestConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\SalesQuoteRequestConnector\Persistence\SalesQuoteRequestConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesQuoteRequestConnector\SalesQuoteRequestConnectorConfig getConfig()
 */
class SalesQuoteRequestConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function getSalesOrderPropelQuery(): SpySalesOrderQuery
    {
        return $this->getProvidedDependency(SalesQuoteRequestConnectorDependencyProvider::PROPEL_QUERY_SALES_ORDER);
    }
}
