<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiPersistenceFactory getFactory()
 */
class ProductLabelGuiRepository extends AbstractRepository implements ProductLabelGuiRepositoryInterface
{
    /**
     * @var string
     */
    protected const ALIAS_CATEGORY_ATTRIBUTE_NAME = 'name';

    /**
     * @var string
     */
    protected const COL_COUNT = 'count';

    /**
     * @param array<int> $productAbstractIds
     * @param int $idLocale
     *
     * @return array<int, array>
     */
    public function getCategoryNamesGroupedByIdProductAbstract(array $productAbstractIds, int $idLocale): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        $productCategoryEntityCollection = $this->getFactory()
            ->getProductCategoryPropelQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->joinSpyCategory()
                ->useSpyCategoryQuery()
                ->joinAttribute()
                    ->useAttributeQuery()
                    ->filterByFkLocale($idLocale)
                    ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, static::ALIAS_CATEGORY_ATTRIBUTE_NAME)
                ->endUse()
            ->endUse()
            ->find();

        $categoryNames = [];
        foreach ($productCategoryEntityCollection as $productCategoryEntity) {
            $categoryNames[$productCategoryEntity->getFkProductAbstract()][] = $productCategoryEntity->getVirtualColumn(static::ALIAS_CATEGORY_ATTRIBUTE_NAME);
        }

        return $categoryNames;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, int>
     */
    public function getAdditionalRelationsCountIndexedByIdProductAbstract(array $productAbstractIds): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        $productLabelProductAbstractCountDataCollection = $this->getFactory()
            ->createProductLabelProductAbstractQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->groupByFkProductAbstract()
            ->select(SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->withColumn('COUNT(*)', static::COL_COUNT)
            ->find();

        $additionalRelationsCount = [];
        foreach ($productLabelProductAbstractCountDataCollection as $productLabelProductAbstractCountData) {
            $idProductAbstract = $productLabelProductAbstractCountData[SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT];
            $additionalRelationsCount[$idProductAbstract] = $productLabelProductAbstractCountData[static::COL_COUNT] - 1;
        }

        return $additionalRelationsCount;
    }
}
