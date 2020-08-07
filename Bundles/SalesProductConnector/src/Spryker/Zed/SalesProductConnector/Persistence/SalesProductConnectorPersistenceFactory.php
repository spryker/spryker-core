<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadataQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface;
use Spryker\Zed\SalesProductConnector\Persistence\Propel\Mapper\ProductMapper;
use Spryker\Zed\SalesProductConnector\Persistence\Propel\Mapper\SalesOrderItemMetadataMapper;
use Spryker\Zed\SalesProductConnector\SalesProductConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesProductConnector\SalesProductConnectorConfig getConfig()
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
     * @return \Spryker\Zed\SalesProductConnector\Persistence\Propel\Mapper\SalesOrderItemMetadataMapper
     */
    public function createSalesOrderItemMetadataMapper(): SalesOrderItemMetadataMapper
    {
        return new SalesOrderItemMetadataMapper(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\SalesProductConnector\Persistence\Propel\Mapper\ProductMapper
     */
    public function createProductMapper(): ProductMapper
    {
        return new ProductMapper();
    }

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(SalesProductConnectorDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface
     */
    public function getUtilEncodingService(): SalesProductConnectorToUtilEncodingInterface
    {
        return $this->getProvidedDependency(SalesProductConnectorDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
