<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Persistence;

use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductLabelGui\ProductLabelGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductLabelGui\ProductLabelGuiConfig getConfig()
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiRepositoryInterface getRepository()
 */
class ProductLabelGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function createProductLabelQuery()
    {
        return SpyProductLabelQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function createProductLabelProductAbstractQuery()
    {
        return SpyProductLabelProductAbstractQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function getProductCategoryPropelQuery(): SpyProductCategoryQuery
    {
        return $this->getProvidedDependency(ProductLabelGuiDependencyProvider::PROPEL_QUERY_PRODUCT_CATEGORY);
    }
}
