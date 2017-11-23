<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Persistence;

use Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductCategoryFilter\ProductCategoryFilterConfig getConfig()
 * @method \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface getQueryContainer()
 */
class ProductCategoryFilterPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function createProductGroupQuery()
    {
        return SpyProductCategoryFilterQuery::create();
    }
}
