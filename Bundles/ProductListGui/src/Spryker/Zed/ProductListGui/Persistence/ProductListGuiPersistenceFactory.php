<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Persistence;

use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductListGui\ProductListGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 */
class ProductListGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function getCategoryPropelQuery(): SpyCategoryQuery
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::PROPEL_QUERY_CATEGORY);
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function getCategoryNodePropelQuery(): SpyCategoryNodeQuery
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::PROPEL_QUERY_CATEGORY_NODE);
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductListGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::PROPEL_QUERY_PRODUCT);
    }
}
