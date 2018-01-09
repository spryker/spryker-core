<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Persistence;

use Orm\Zed\ProductSetPageSearch\Persistence\SpyProductSetPageSearchQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductSetPageSearch\ProductSetPageSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSetPageSearch\ProductSetPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductSetPageSearch\Persistence\ProductSetPageSearchQueryContainerInterface getQueryContainer()
 */
class ProductSetPageSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductSetPageSearch\Persistence\SpyProductSetPageSearchQuery
     */
    public function createProductSetPageSearch()
    {
        return SpyProductSetPageSearchQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Dependency\QueryContainer\ProductSetPageSearchToProductImageQueryContainerInterface
     */
    public function getProductImageQueryContainer()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::QUERY_CONTAINER_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Dependency\QueryContainer\ProductSetPageSearchToProductSetQueryContainerInterface
     */
    public function getProductSetQueryContainer()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::QUERY_CONTAINER_PRODUCT_SET);
    }
}
