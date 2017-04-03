<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AbstractProductDataFeed\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\AbstractProductDataFeed\AbstractProductDataFeedDependencyProvider;

/**
 * @method \Spryker\Zed\AbstractProductDataFeed\AbstractProductDataFeedConfig getConfig()
 * @method \Spryker\Zed\AbstractProductDataFeed\Persistence\AbstractProductDataFeedQueryContainer getQueryContainer()
 */
class AbstractProductDataFeedPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(AbstractProductDataFeedDependencyProvider::PRODUCT_QUERY_CONTAINER);
    }

    /**
     * @return \Spryker\Zed\AbstractProductDataFeed\Persistence\AbstractProductJoinQuery
     */
    public function getAbstractProductJoinQuery()
    {
        $abstractProductJoinQuery = new AbstractProductJoinQuery();

        return $abstractProductJoinQuery;
    }

}
