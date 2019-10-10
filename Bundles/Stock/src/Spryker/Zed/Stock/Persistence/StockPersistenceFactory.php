<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Persistence;

use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Orm\Zed\Stock\Persistence\SpyStockStoreQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Stock\Persistence\Propel\Mapper\StockMapper;
use Spryker\Zed\Stock\Persistence\Propel\Mapper\StockProductMapper;
use Spryker\Zed\Stock\Persistence\Propel\Mapper\StockStoreRelationMapper;

/**
 * @method \Spryker\Zed\Stock\StockConfig getConfig()
 * @method \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Stock\Persistence\StockRepositoryInterface getRepository()
 * @method \Spryker\Zed\Stock\Persistence\StockEntityManagerInterface getEntityManager()
 */
class StockPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function createStockProductQuery()
    {
        return SpyStockProductQuery::create();
    }

    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    public function createStockQuery()
    {
        return SpyStockQuery::create();
    }

    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockStoreQuery
     */
    public function createStockStoreQuery(): SpyStockStoreQuery
    {
        return SpyStockStoreQuery::create();
    }

    /**
     * @return \Spryker\Zed\Stock\Persistence\Propel\Mapper\StockMapper
     */
    public function createStockMapper(): StockMapper
    {
        return new StockMapper($this->createStockStoreRelationMapper());
    }

    /**
     * @return \Spryker\Zed\Stock\Persistence\Propel\Mapper\StockStoreRelationMapper
     */
    public function createStockStoreRelationMapper(): StockStoreRelationMapper
    {
        return new StockStoreRelationMapper();
    }

    /**
     * @return \Spryker\Zed\Stock\Persistence\Propel\Mapper\StockProductMapper
     */
    public function createStockProductMapper(): StockProductMapper
    {
        return new StockProductMapper();
    }
}
