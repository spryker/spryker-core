<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Persistence;

use Spryker\Zed\DataFeed\Persistence\QueryBuilder\ProductQueryBuilder;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\DataFeed\DataFeedDependencyProvider;

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
     * @return ProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(DataFeedDependencyProvider::PRODUCT_QUERY_CONTAINER);
    }

}
