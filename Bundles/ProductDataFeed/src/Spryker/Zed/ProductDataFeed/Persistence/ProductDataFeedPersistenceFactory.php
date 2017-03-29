<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDataFeed\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductDataFeed\ProductDataFeedDependencyProvider;

/**
 * @method \Spryker\Zed\ProductDataFeed\ProductDataFeedConfig getConfig()
 * @method \Spryker\Zed\ProductDataFeed\Persistence\ProductDataFeedQueryContainer getQueryContainer()
 */
class ProductDataFeedPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductDataFeedDependencyProvider::PRODUCT_QUERY_CONTAINER);
    }

}
