<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductImageFilterTransfer;
use Generated\Shared\Transfer\ProductImageSetCollectionTransfer;
use Generated\Shared\Transfer\ProductImageSetConditionsTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImagePersistenceFactory getFactory()
 */
class ProductImageRepository extends AbstractRepository implements ProductImageRepositoryInterface
{
    /**
     * @var string
     */
    protected const FK_PRODUCT_CONCRETE = 'fkProductConcrete';

    /**
     * @param array<int> $productIds
     * @param int $idLocale
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
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
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductImage\Persistence\SpyProductImageSet> $productImageSetEntities
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    protected function mapProductImageSetTransfers(ObjectCollection $productImageSetEntities): array
    {
        $mapper = $this->getFactory()->createProductImageMapper();
        $productImageSetTransfers = [];
        foreach ($productImageSetEntities as $productImageSetEntity) {
            $productImageSetTransfer = new ProductImageSetTransfer();
            $productImageSetTransfers[] = $mapper->mapProductImageSetEntityToProductImageSetTransfer(
                $productImageSetEntity,
                $productImageSetTransfer,
            );
        }

        return $productImageSetTransfers;
    }

    /**
     * @param list<int> $productSetIds
     *
     * @return array<int, list<\Generated\Shared\Transfer\ProductImageTransfer>>
     */
    public function getProductImagesByProductSetIds(array $productSetIds): array
    {
        $productImageSetToProductImageEntities = $this->getFactory()
            ->createProductImageSetToProductImageQuery()
            ->joinWithSpyProductImage()
            ->filterByFkProductImageSet_In($productSetIds)
            ->orderBySortOrder(Criteria::ASC)
            ->find();

        if ($productImageSetToProductImageEntities->count() === 0) {
            return [];
        }

        return $this->indexProductImagesByProductImageSetId($productImageSetToProductImageEntities);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage> $productImageSetToProductImageEntities
     *
     * @return array<array<\Generated\Shared\Transfer\ProductImageTransfer>>
     */
    protected function indexProductImagesByProductImageSetId(ObjectCollection $productImageSetToProductImageEntities): array
    {
        $mapper = $this->getFactory()->createProductImageMapper();
        $indexedProductImages = [];
        foreach ($productImageSetToProductImageEntities as $productImageSetToProductImageEntity) {
            $productImageTransfer = $mapper->mapProductImageEntityToProductImageTransfer(
                $productImageSetToProductImageEntity->getSpyProductImage(),
                new ProductImageTransfer(),
            );
            $indexedProductImages[$productImageSetToProductImageEntity->getFkProductImageSet()][] = $productImageTransfer;
        }

        return $indexedProductImages;
    }

    /**
     * Result formats:
     * [
     *     $idProduct => [ProductImageSet, ...],
     *     ...,
     * ]
     *
     * @param array<int> $productIds
     *
     * @return array<array<\Orm\Zed\ProductImage\Persistence\SpyProductImageSet>>
     */
    public function getProductImageSetsGroupedByIdProduct(array $productIds): array
    {
        $productImageSetEntities = $this->getFactory()->createProductImageSetQuery()
            ->filterByFkProduct_In($productIds)
            ->find();

        $result = [];

        foreach ($productImageSetEntities as $productImageSetEntity) {
            $result[$productImageSetEntity->getFkProduct()][] = $productImageSetEntity;
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageFilterTransfer $productImageFilterTransfer
     *
     * @return array<int>
     */
    public function getProductConcreteIds(ProductImageFilterTransfer $productImageFilterTransfer): array
    {
        $productImageSetQuery = $this->getFactory()->createProductImageSetQuery();

        if ($productImageFilterTransfer->getProductImageSetIds()) {
            $productImageSetQuery->filterByIdProductImageSet_In($productImageFilterTransfer->getProductImageSetIds());
        }

        if ($productImageFilterTransfer->getProductImageIds()) {
            $productImageSetQuery
                ->useSpyProductImageSetToProductImageQuery()
                    ->filterByFkProductImage_In($productImageFilterTransfer->getProductImageIds())
                ->endUse();
        }

        return $productImageSetQuery
            ->withColumn(Criteria::DISTINCT . ' ' . SpyProductImageSetTableMap::COL_FK_PRODUCT, static::FK_PRODUCT_CONCRETE)
            ->filterByFkProduct(null, Criteria::ISNOTNULL)
            ->select([static::FK_PRODUCT_CONCRETE])
            ->find()
            ->getData();
    }

    /**
     * @module Locale
     * @module Product
     *
     * @param \Generated\Shared\Transfer\ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetCollectionTransfer
     */
    public function getConcreteProductImageSetCollection(
        ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
    ): ProductImageSetCollectionTransfer {
        $productImageSetCollectionTransfer = new ProductImageSetCollectionTransfer();
        $productImageSetQuery = $this->getFactory()
            ->createProductImageSetQuery()
            ->leftJoinWithSpyLocale()
            ->joinWithSpyProduct();

        $productImageSetQuery = $this->applyConcreteProductImageSetCriteriaFilters(
            $productImageSetQuery,
            $productImageSetCriteriaTransfer,
        );

        $productImageSetQuery = $this->applyProductImageSetSorting(
            $productImageSetQuery,
            $productImageSetCriteriaTransfer->getSortCollection(),
        );

        $paginationTransfer = $productImageSetCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $productImageSetQuery = $this->applyProductImageSetPagination($productImageSetQuery, $paginationTransfer);
            $productImageSetCollectionTransfer->setPagination($paginationTransfer);
        }

        $productImageSetEntityCollection = $productImageSetQuery->find();
        if ($productImageSetEntityCollection->count() === 0) {
            return $productImageSetCollectionTransfer;
        }

        return $this->getFactory()
            ->createProductImageSetMapper()
            ->mapConcreteProductImageSetEntityCollectionToProductImageSetCollectionTransfer(
                $productImageSetEntityCollection,
                $productImageSetCollectionTransfer,
            );
    }

    /**
     * @module Product
     *
     * @param \Generated\Shared\Transfer\ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetCollectionTransfer
     */
    public function getAbstractProductImageSetCollection(
        ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
    ): ProductImageSetCollectionTransfer {
        $productImageSetCollectionTransfer = new ProductImageSetCollectionTransfer();
        $productImageSetQuery = $this->getFactory()->createProductImageSetQuery();

        $productImageSetQuery = $this->applyAbstractProductImageSetCriteriaFilters(
            $productImageSetQuery,
            $productImageSetCriteriaTransfer,
        );

        $productImageSetQuery = $this->applyProductImageSetSorting(
            $productImageSetQuery,
            $productImageSetCriteriaTransfer->getSortCollection(),
        );

        $paginationTransfer = $productImageSetCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $productImageSetQuery = $this->applyProductImageSetPagination($productImageSetQuery, $paginationTransfer);
            $productImageSetCollectionTransfer->setPagination($paginationTransfer);
        }

        $productImageSetEntityCollection = $productImageSetQuery->find();
        if ($productImageSetEntityCollection->count() === 0) {
            return $productImageSetCollectionTransfer;
        }

        return $this->getFactory()
            ->createProductImageSetMapper()
            ->mapAbstractProductImageSetEntityCollectionToProductImageSetCollectionTransfer(
                $productImageSetEntityCollection,
                $productImageSetCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery $productImageSetQuery
     * @param \Generated\Shared\Transfer\ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    protected function applyConcreteProductImageSetCriteriaFilters(
        SpyProductImageSetQuery $productImageSetQuery,
        ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
    ): SpyProductImageSetQuery {
        $productImageSetConditionsTransfer = $productImageSetCriteriaTransfer->getProductImageSetConditions();
        if (!$productImageSetConditionsTransfer) {
            return $productImageSetQuery;
        }

        if ($productImageSetConditionsTransfer->getSkus()) {
            $productImageSetQuery
                ->useSpyProductQuery()
                    ->filterBySku_In($productImageSetConditionsTransfer->getSkus())
                ->endUse();
        }

        if ($productImageSetConditionsTransfer->getLocaleNames()) {
            $productImageSetQuery
                ->useSpyLocaleQuery()
                    ->filterByLocaleName_In($productImageSetConditionsTransfer->getLocaleNames())
                ->endUse();
        }

        if ($productImageSetConditionsTransfer->getNames()) {
            $productImageSetQuery
                ->filterByName_In($productImageSetConditionsTransfer->getNames());
        }

        if ($productImageSetConditionsTransfer->getProductConcreteIds()) {
            $productImageSetQuery
                ->filterByFkProduct_In($productImageSetConditionsTransfer->getProductConcreteIds());
        }

        if ($productImageSetConditionsTransfer->getLocaleIds()) {
            $productImageSetQuery
                ->filterByFkLocale_In($productImageSetConditionsTransfer->getLocaleIds());
            $productImageSetQuery = $this->addFallbackLocaleCondition($productImageSetQuery, $productImageSetConditionsTransfer);
        }

        return $productImageSetQuery;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery $productImageSetQuery
     * @param \Generated\Shared\Transfer\ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    protected function applyAbstractProductImageSetCriteriaFilters(
        SpyProductImageSetQuery $productImageSetQuery,
        ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
    ): SpyProductImageSetQuery {
        $productImageSetConditionsTransfer = $productImageSetCriteriaTransfer->getProductImageSetConditions();
        if (!$productImageSetConditionsTransfer) {
            return $productImageSetQuery;
        }

        if ($productImageSetConditionsTransfer->getProductAbstractIds()) {
            $productImageSetQuery
                ->filterByFkProductAbstract_In($productImageSetConditionsTransfer->getProductAbstractIds());
        }

        if ($productImageSetConditionsTransfer->getNames()) {
            $productImageSetQuery->filterByName_In($productImageSetConditionsTransfer->getNames());
        }

        if ($productImageSetConditionsTransfer->getLocaleIds()) {
            $productImageSetQuery
                ->filterByFkLocale_In($productImageSetConditionsTransfer->getLocaleIds());
            $productImageSetQuery = $this->addFallbackLocaleCondition($productImageSetQuery, $productImageSetConditionsTransfer);
        }

        return $productImageSetQuery;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery $productImageSetQuery
     * @param \ArrayObject<\Generated\Shared\Transfer\SortTransfer> $sortTransfers
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    protected function applyProductImageSetSorting(
        SpyProductImageSetQuery $productImageSetQuery,
        ArrayObject $sortTransfers
    ): SpyProductImageSetQuery {
        foreach ($sortTransfers as $sortTransfer) {
            $productImageSetQuery->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $productImageSetQuery;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery $productImageSetQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyProductImageSetPagination(
        SpyProductImageSetQuery $productImageSetQuery,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $paginationTransfer->setNbResults($productImageSetQuery->count());

            $productImageSetQuery->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());

            return $productImageSetQuery;
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage()) {
            $paginationModel = $productImageSetQuery->paginate(
                $paginationTransfer->getPage(),
                $paginationTransfer->getMaxPerPage(),
            );

            $paginationTransfer->setNbResults($paginationModel->getNbResults())
                ->setFirstIndex($paginationModel->getFirstIndex())
                ->setLastIndex($paginationModel->getLastIndex())
                ->setFirstPage($paginationModel->getFirstPage())
                ->setLastPage($paginationModel->getLastPage())
                ->setNextPage($paginationModel->getNextPage())
                ->setPreviousPage($paginationModel->getPreviousPage());

            return $paginationModel->getQuery();
        }

        return $productImageSetQuery;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery $productImageSetQuery
     * @param \Generated\Shared\Transfer\ProductImageSetConditionsTransfer $productImageSetConditionsTransfer
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    protected function addFallbackLocaleCondition(
        SpyProductImageSetQuery $productImageSetQuery,
        ProductImageSetConditionsTransfer $productImageSetConditionsTransfer
    ): SpyProductImageSetQuery {
        if ($productImageSetConditionsTransfer->getAddFallbackLocale()) {
            $productImageSetQuery->_or()->filterByFkLocale(null, Criteria::ISNULL);
        }

        return $productImageSetQuery;
    }
}
