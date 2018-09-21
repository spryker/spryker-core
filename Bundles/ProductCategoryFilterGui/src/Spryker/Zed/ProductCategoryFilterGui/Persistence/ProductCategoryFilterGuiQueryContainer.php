<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Persistence;

use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\Persistence\ProductCategoryFilterGuiPersistenceFactory getFactory()
 */
class ProductCategoryFilterGuiQueryContainer extends AbstractQueryContainer implements ProductCategoryFilterGuiQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idCategory
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryCategoryByIdAndLocale($idCategory, $idLocale)
    {
        return $this->getFactory()->getCategoryQueryContainer()
                ->queryAttributeByCategoryId($idCategory)
                ->joinLocale()
                ->filterByFkLocale($idLocale)
                ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME);
    }

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryProductAbstractsInCategory($idCategory)
    {
        return $this->getFactory()->getProductCategoryQueryContainer()
            ->queryProductCategoryMappings()
            ->filterByFkCategory($idCategory)
            ->select([SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT]);
    }
}
