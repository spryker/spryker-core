<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStoragePersistenceFactory getFactory()
 */
class ProductStorageRepository extends AbstractRepository implements ProductStorageRepositoryInterface
{
    /**
     * @var string
     */
    protected const URL_RELATION = 'SpyProductAbstract.SpyUrl';

    /**
     * @var string
     */
    protected const COLUMN_URL = 'url';

    /**
     * @var string
     */
    protected const ENTITY_URL = 'SpyUrl';

    /**
     * @var string
     */
    protected const COL_PRODUCT_COUNT = 'productCount';

    /**
     * @var string
     */
    protected const COL_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int|string|bool>
     */
    public function getProductAbstractsByIds(array $productAbstractIds): array
    {
        return $this->getFactory()->getProductAbstractLocalizedAttributesPropelQuery()
            ->joinWithLocale()
            ->joinWithSpyProductAbstract()
            ->useSpyProductAbstractQuery()
                ->joinWithSpyProductAbstractStore()
                ->useSpyProductAbstractStoreQuery()
                    ->joinWithSpyStore()
                ->endUse()
            ->endUse()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY)
            ->join(static::URL_RELATION)
            ->addJoinCondition(static::ENTITY_URL, sprintf('%s = %s', SpyUrlTableMap::COL_FK_LOCALE, SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE))
            ->withColumn(SpyUrlTableMap::COL_URL, static::COLUMN_URL)
            ->find()
            ->getData();
    }

    /**
     * @param array<int> $idProductAbstracts
     *
     * @return array<array<string, int>>
     */
    public function getProductConcretesCountByIdProductAbstracts(array $idProductAbstracts): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $productConcretesCollection */
        $productConcretesCollection = $this->getFactory()->getProductPropelQuery()
            ->filterByFkProductAbstract_In($idProductAbstracts)
            ->filterByIsActive(true)
            ->withColumn('COUNT(*)', static::COL_PRODUCT_COUNT)
            ->select([static::COL_FK_PRODUCT_ABSTRACT, static::COL_PRODUCT_COUNT])
            ->groupByFkProductAbstract()
            ->find();

        return $productConcretesCollection->toArray();
    }
}
