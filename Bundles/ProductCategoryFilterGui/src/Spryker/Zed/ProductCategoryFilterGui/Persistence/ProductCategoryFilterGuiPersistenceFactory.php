<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Persistence;

use Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductCategoryFilterGui\ProductCategoryFilterGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\ProductCategoryFilterGuiConfig getConfig()
 * @method \Spryker\Zed\ProductCategoryFilterGui\Persistence\ProductCategoryFilterGuiQueryContainerInterface getQueryContainer()
 */
class ProductCategoryFilterGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ProductCategoryFilterGui\Dependency\QueryContainer\ProductCategoryFilterGuiToCategoryInterface
     */
    public function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductCategoryFilterGuiDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function createFilterQuery()
    {
        return SpyCategoryAttributeQuery::create();
    }
}
