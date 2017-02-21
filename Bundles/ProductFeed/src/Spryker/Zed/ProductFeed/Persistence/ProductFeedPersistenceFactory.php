<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductFeed\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductFeed\ProductFeedDependencyProvider;

/**
 * @method \Spryker\Zed\ProductFeed\ProductFeedConfig getConfig()
 * @method \Spryker\Zed\ProductFeed\Persistence\ProductFeedQueryContainer getQueryContainer()
 */
class ProductFeedPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return ProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductFeedDependencyProvider::PRODUCT_QUERY_CONTAINER);
    }

}
