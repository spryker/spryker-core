<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Product\Persistence\ProductPersistenceFactory getFactory()
 */
class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    public const KEY_FILTERED_PRODUCTS_RESULT = 'result';
    public const KEY_FILTERED_PRODUCTS_PRODUCT_NAME = 'name';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $search
     * @param int $limit
     *
     * @return array
     */
    public function findProductAbstractDataBySkuOrLocalizedName(string $search, LocaleTransfer $localeTransfer, int $limit): array
    {
        $criteria = new Criteria();
        $skuLikeCriteria = $criteria->getNewCriterion(
            SpyProductAbstractTableMap::COL_SKU,
            '%' . $search . '%',
            Criteria::LIKE
        );

        $productAbstractQuery = $this->getFactory()
            ->createProductAbstractQuery()
            ->leftJoinSpyProductAbstractLocalizedAttributes()
            ->addJoinCondition(
                'SpyProductAbstractLocalizedAttributes',
                sprintf('SpyProductAbstractLocalizedAttributes.fk_locale = %d', $localeTransfer->getIdLocale())
            )
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::KEY_FILTERED_PRODUCTS_PRODUCT_NAME)
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, static::KEY_FILTERED_PRODUCTS_RESULT)
            ->where('lower(' . SpyProductAbstractLocalizedAttributesTableMap::COL_NAME . ') like ?', '%' . mb_strtolower($search) . '%')
            ->addOr($skuLikeCriteria)
            ->limit($limit)
            ->select([
                 static::KEY_FILTERED_PRODUCTS_RESULT,
                 static::KEY_FILTERED_PRODUCTS_PRODUCT_NAME,
             ]);

        return $productAbstractQuery->find()
            ->toArray();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $search
     * @param int $limit
     *
     * @return array
     */
    public function findProductConcreteDataBySkuOrLocalizedName(string $search, LocaleTransfer $localeTransfer, int $limit): array
    {
        $criteria = new Criteria();
        $skuLikeCriteria = $criteria->getNewCriterion(
            SpyProductTableMap::COL_SKU,
            '%' . $search . '%',
            Criteria::LIKE
        );

        $productAbstractQuery = $this->getFactory()
            ->createProductQuery()
            ->leftJoinSpyProductLocalizedAttributes()
            ->addJoinCondition(
                'SpyProductLocalizedAttributes',
                sprintf('SpyProductLocalizedAttributes.fk_locale = %d', $localeTransfer->getIdLocale())
            )
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, static::KEY_FILTERED_PRODUCTS_PRODUCT_NAME)
            ->withColumn(SpyProductTableMap::COL_SKU, static::KEY_FILTERED_PRODUCTS_RESULT)
            ->where('lower(' . SpyProductLocalizedAttributesTableMap::COL_NAME . ') like ?', '%' . mb_strtolower($search) . '%')
            ->addOr($skuLikeCriteria)
            ->limit($limit)
            ->select([
                 static::KEY_FILTERED_PRODUCTS_RESULT,
                 static::KEY_FILTERED_PRODUCTS_PRODUCT_NAME,
             ]);

        return $productAbstractQuery->find()
            ->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return bool
     */
    public function isProductConcreteActive(ProductConcreteTransfer $productConcreteTransfer): bool
    {
        return $this->getFactory()
            ->createProductQuery()
            ->findOneBySku($productConcreteTransfer->getSku())
            ->getIsActive();
    }
}
