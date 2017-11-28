<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadataQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesProductConnector\SalesProductConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface getQueryContainer()
 */
class SalesProductConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadataQuery
     */
    public function createProductMetadataQuery()
    {
        return SpySalesOrderItemMetadataQuery::create();
    }

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(SalesProductConnectorDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }
}
