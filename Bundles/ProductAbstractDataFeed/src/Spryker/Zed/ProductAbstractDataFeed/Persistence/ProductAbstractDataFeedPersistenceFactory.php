<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAbstractDataFeed\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductAbstractDataFeed\ProductAbstractDataFeedDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAbstractDataFeed\ProductAbstractDataFeedConfig getConfig()
 * @method \Spryker\Zed\ProductAbstractDataFeed\Persistence\ProductAbstractDataFeedQueryContainerInterface getQueryContainer()
 */
class ProductAbstractDataFeedPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ProductAbstractDataFeed\Dependency\QueryContainer\ProductAbstractDataFeedToProductInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductAbstractDataFeedDependencyProvider::PRODUCT_QUERY_CONTAINER);
    }

    /**
     * @return \Spryker\Zed\ProductAbstractDataFeed\Persistence\ProductAbstractJoinQueryInterface
     */
    public function createAbstractProductJoinQuery(): ProductAbstractJoinQueryInterface
    {
        return new ProductAbstractJoinQuery();
    }
}
