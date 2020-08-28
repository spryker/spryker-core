<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterPersistenceFactory getFactory()
 */
class ProductCategoryFilterQueryContainer extends AbstractQueryContainer implements ProductCategoryFilterQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryFilterByCategoryId($idCategory)
    {
        return $this->createProductGroupQuery()->filterByFkCategory($idCategory);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryFilter()
    {
        return $this->createProductGroupQuery();
    }

    /**
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    protected function createProductGroupQuery()
    {
        return $this->getFactory()->createProductGroupQuery();
    }
}
