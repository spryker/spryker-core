<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryPersistenceFactory getFactory()
 */
class ProductCategoryRepository extends AbstractRepository implements ProductCategoryRepositoryInterface
{
    protected const TABLE_JOIN_CATEGORY = 'Category';

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryTransferCollectionByIdProductAbstract(int $idProductAbstract, int $idLocale): CategoryCollectionTransfer
    {
        $spyCategoryCollection = $this->queryCategoriesByIdProductAbstract($idProductAbstract, $idLocale)->find();

        return $this->getFactory()
            ->createCategoryMapper()
            ->mapCategoryCollection($spyCategoryCollection, new CategoryCollectionTransfer());
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    protected function queryCategoriesByIdProductAbstract(int $idProductAbstract, int $idLocale): SpyProductCategoryQuery
    {
        return $this->getFactory()->createProductCategoryQuery()
            ->innerJoinWithSpyCategory()
            ->useSpyCategoryQuery()
            ->joinAttribute()
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name')
            ->addAnd(
                SpyCategoryAttributeTableMap::COL_FK_LOCALE,
                $idLocale,
                Criteria::EQUAL
            )
            ->addAscendingOrderByColumn(SpyCategoryAttributeTableMap::COL_NAME)
            ->endUse()
            ->addAnd(
                SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT,
                $idProductAbstract,
                Criteria::EQUAL
            )
            ->groupByFkCategory()
            ->groupBy(SpyCategoryAttributeTableMap::COL_NAME);
    }
}
