<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Persistence;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImagePersistenceFactory getFactory()
 */
class ProductImageRepository extends AbstractRepository implements ProductImageRepositoryInterface
{
    protected const FK_PRODUCT_CONCRETE = 'fkProductConcrete';

    /**
     * @param int[] $productIds
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetTransfersByProductIdsAndIdLocale(array $productIds, int $idLocale): array
    {
        $productImageSetEntities = $this->getFactory()
            ->createProductImageSetQuery()
            ->filterByFkProduct_In($productIds)
            ->condition('isCurrentLocale', sprintf('%s = ?', SpyProductImageSetTableMap::COL_FK_LOCALE), $idLocale)
            ->condition('isLocaleNull', sprintf('%s IS NULL', SpyProductImageSetTableMap::COL_FK_LOCALE))
            ->combine(['isCurrentLocale', 'isLocaleNull'], Criteria::LOGICAL_OR)
            ->find();

        if ($productImageSetEntities->count() === 0) {
            return [];
        }

        return $this->mapProductImageSetTransfers($productImageSetEntities);
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet[]|\Propel\Runtime\Collection\ObjectCollection $productImageSetEntities
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    protected function mapProductImageSetTransfers(ObjectCollection $productImageSetEntities): array
    {
        $mapper = $this->getFactory()->createProductImageMapper();
        $productImageSetTransfers = [];
        foreach ($productImageSetEntities as $productImageSetEntity) {
            $productImageSetTransfer = new ProductImageSetTransfer();
            $productImageSetTransfers[] = $mapper->mapProductImageSetEntityToProductImageSetTransfer($productImageSetEntity, $productImageSetTransfer);
        }

        return $productImageSetTransfers;
    }

    /**
     * @param int[] $productSetIds
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer[][]
     */
    public function getProductImagesByProductSetIds(array $productSetIds): array
    {
        $productImageSetToProductImageEntities = $this->getFactory()
            ->createProductImageSetToProductImageQuery()
            ->joinWithSpyProductImage()
            ->filterByFkProductImageSet_In($productSetIds)
            ->orderBySortOrder(Criteria::DESC)
            ->find();

        if ($productImageSetToProductImageEntities->count() === 0) {
            return [];
        }

        return $this->indexProductImagesByProductImageSetId($productImageSetToProductImageEntities);
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage[]|\Propel\Runtime\Collection\ObjectCollection $productImageSetToProductImageEntities
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer[][]
     */
    protected function indexProductImagesByProductImageSetId(ObjectCollection $productImageSetToProductImageEntities): array
    {
        $mapper = $this->getFactory()->createProductImageMapper();
        $indexedProductImages = [];
        foreach ($productImageSetToProductImageEntities as $productImageSetToProductImageEntity) {
            $productImageTransfer = $mapper->mapProductImageEntityToProductImageTransfer(
                $productImageSetToProductImageEntity->getSpyProductImage(),
                new ProductImageTransfer()
            );
            $indexedProductImages[$productImageSetToProductImageEntity->getFkProductImageSet()][] = $productImageTransfer;
        }

        return $indexedProductImages;
    }

    /**
     * @param int[] $productImageIds
     *
     * @return int[]
     */
    public function getProductConcreteIdsByProductImageIds(array $productImageIds): array
    {
        if (!$productImageIds) {
            return [];
        }

        return $this->getFactory()
            ->createProductImageQuery()
            ->useSpyProductImageSetToProductImageQuery()
                ->filterByFkProductImage_In($productImageIds)
                ->innerJoinSpyProductImageSet()
                ->withColumn(Criteria::DISTINCT . ' ' . SpyProductImageSetTableMap::COL_FK_PRODUCT, static::FK_PRODUCT_CONCRETE)
                ->addAnd(SpyProductImageSetTableMap::COL_FK_PRODUCT, null, Criteria::NOT_EQUAL)
            ->endUse()
            ->select([static::FK_PRODUCT_CONCRETE])
            ->find()
            ->getData();
    }

    /**
     * @param int[] $productImageSetIds
     *
     * @return int[]
     */
    public function getProductConcreteIdsByProductImageSetIds(array $productImageSetIds): array
    {
        if (!$productImageSetIds) {
            return [];
        }

        return $this->getFactory()
            ->createProductImageSetQuery()
            ->filterByIdProductImageSet_In($productImageSetIds)
            ->withColumn(Criteria::DISTINCT . ' ' . SpyProductImageSetTableMap::COL_FK_PRODUCT, static::FK_PRODUCT_CONCRETE)
            ->addAnd(SpyProductImageSetTableMap::COL_FK_PRODUCT, null, Criteria::NOT_EQUAL)
            ->select([static::FK_PRODUCT_CONCRETE])
            ->find()
            ->getData();
    }
}
