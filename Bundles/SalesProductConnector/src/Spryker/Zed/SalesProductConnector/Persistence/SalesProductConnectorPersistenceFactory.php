<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadataQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface;
use Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilTextServiceInterface;
use Spryker\Zed\SalesProductConnector\Persistence\Propel\Mapper\ProductMapper;
use Spryker\Zed\SalesProductConnector\Persistence\Propel\Mapper\SalesOrderItemMetadataMapper;
use Spryker\Zed\SalesProductConnector\SalesProductConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesProductConnector\SalesProductConnectorConfig getConfig()
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorEntityManagerInterface getEntityManager()
 */
class SalesProductConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadataQuery
     */
    public function createProductMetadataQuery(): SpySalesOrderItemMetadataQuery
    {
        return SpySalesOrderItemMetadataQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function createSalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return SpySalesOrderItemQuery::create();
    }

    /**
     * @return \Spryker\Zed\SalesProductConnector\Persistence\Propel\Mapper\SalesOrderItemMetadataMapper
     */
    public function createSalesOrderItemMetadataMapper(): SalesOrderItemMetadataMapper
    {
        return new SalesOrderItemMetadataMapper(
            $this->getUtilEncodingService(),
            $this->getConfig(),
            $this->getUtilTextService(),
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

    /**
     * @return \Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilTextServiceInterface
     */
    public function getUtilTextService(): SalesProductConnectorToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(SalesProductConnectorDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface
     */
    public function getSalesProductConnectorQueryContainer(): SalesProductConnectorQueryContainerInterface
    {
        return $this->getQueryContainer();
    }
}
