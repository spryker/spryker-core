<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductApi\ProductApiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductApi\ProductApiConfig getConfig()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainer getQueryContainer()
 */
class ProductApiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function createProductAbstractQuery()
    {
        return SpyProductAbstractQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface
     */
    public function getApiQueryContainer()
    {
        return $this->getProvidedDependency(ProductApiDependencyProvider::QUERY_CONTAINER_API);
    }
}
