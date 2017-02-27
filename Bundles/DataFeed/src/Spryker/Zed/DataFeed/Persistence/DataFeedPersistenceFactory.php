<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Persistence;

use Spryker\Zed\DataFeed\DataFeedDependencyProvider;
use Spryker\Zed\DataFeed\Persistence\QueryBuilder\CategoryQueryBuilder;
use Spryker\Zed\DataFeed\Persistence\QueryBuilder\PriceQueryBuilder;
use Spryker\Zed\DataFeed\Persistence\QueryBuilder\ProductQueryBuilder;
use Spryker\Zed\DataFeed\Persistence\QueryBuilder\StockQueryBuilder;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\DataFeed\DataFeedConfig getConfig()
 * @method \Spryker\Zed\DataFeed\Persistence\DataFeedQueryContainer getQueryContainer()
 */
class DataFeedPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Spryker\Zed\DataFeed\Persistence\QueryBuilder\ProductQueryBuilder
     */
    public function createProductQueryBuilder()
    {
        return new ProductQueryBuilder(
            $this->getProductQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\DataFeed\Persistence\QueryBuilder\CategoryQueryBuilder
     */
    public function createCategoryQueryBuilder()
    {
        return new CategoryQueryBuilder(
            $this->getCategoryQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\DataFeed\Persistence\QueryBuilder\StockQueryBuilder
     */
    public function createStockQueryBuilder()
    {
        return new StockQueryBuilder(
            $this->getStockQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\DataFeed\Persistence\QueryBuilder\PriceQueryBuilder
     */
    public function createPriceQueryBuilder()
    {
        return new PriceQueryBuilder(
            $this->getPriceQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(DataFeedDependencyProvider::PRODUCT_QUERY_CONTAINER);
    }

    /**
     * @return \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    public function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(DataFeedDependencyProvider::CATEGORY_QUERY_CONTAINER);
    }

    /**
     * @return \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface
     */
    public function getStockQueryContainer()
    {
        return $this->getProvidedDependency(DataFeedDependencyProvider::STOCK_QUERY_CONTAINER);
    }

    /**
     * @return \Spryker\Zed\Price\Persistence\PriceQueryContainerInterface
     */
    public function getPriceQueryContainer()
    {
        return $this->getProvidedDependency(DataFeedDependencyProvider::PRICE_QUERY_CONTAINER);
    }

}
