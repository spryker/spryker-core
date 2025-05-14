<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOfferShipmentType\Persistence\Map\SpyProductOfferShipmentTypeTableMap;
use Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypePersistenceFactory getFactory()
 */
class ProductOfferShipmentTypeRepository extends AbstractRepository implements ProductOfferShipmentTypeRepositoryInterface
{
    /**
     * @var string
     */
    public const SHIPMENT_TYPE_IDS_GROUPED = 'shipmentTypeIdsGrouped';

    /**
     * @param int $idProductOffer
     *
     * @return array<int>
     */
    public function getShipmentTypeIdsByIdProductOffer(int $idProductOffer): array
    {
        return $this->getFactory()
            ->createProductOfferShipmentTypeQuery()
            ->filterByFkProductOffer($idProductOffer)
            ->select([SpyProductOfferShipmentTypeTableMap::COL_FK_SHIPMENT_TYPE])
            ->find()
            ->getData();
    }

    /**
     * @module Product
     * @module ProductOffer
     * @module ShipmentType
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function getProductOfferShipmentTypeCollection(
        ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
    ): ProductOfferShipmentTypeCollectionTransfer {
        $productOfferShipmentTypeQuery = $this->getFactory()
            ->createProductOfferShipmentTypeQuery()
            ->leftJoinProductOffer()
            ->select([
                SpyProductOfferShipmentTypeTableMap::COL_ID_PRODUCT_OFFER_SHIPMENT_TYPE,
                SpyProductOfferShipmentTypeTableMap::COL_FK_PRODUCT_OFFER,
                SpyProductOfferShipmentTypeTableMap::COL_FK_SHIPMENT_TYPE,
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE,
            ]);
        $productOfferShipmentTypeQuery = $this->applyProductOfferShipmentTypeFilters(
            $productOfferShipmentTypeQuery,
            $productOfferShipmentTypeCriteriaTransfer,
        );
        $productOfferShipmentTypeQuery = $this->applyProductOfferShipmentTypeSorting(
            $productOfferShipmentTypeQuery,
            $productOfferShipmentTypeCriteriaTransfer,
        );

        $productOfferShipmentTypeCollectionTransfer = new ProductOfferShipmentTypeCollectionTransfer();
        $paginationTransfer = $productOfferShipmentTypeCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $productOfferShipmentTypeQuery = $this->applyProductOfferShipmentTypePagination($productOfferShipmentTypeQuery, $paginationTransfer);
            $productOfferShipmentTypeCollectionTransfer->setPagination($paginationTransfer);
        }

        $productOfferShipmentTypeData = $productOfferShipmentTypeQuery
            ->setFormatter(SimpleArrayFormatter::class)
            ->find();

        return $this->getFactory()
            ->createProductOfferShipmentTypeMapper()
            ->mapProductOfferShipmentTypeDataCollectionToProductOfferShipmentTypeCollectionTransfer(
                $productOfferShipmentTypeData,
                $productOfferShipmentTypeCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery $productOfferShipmentTypeQuery
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery
     */
    protected function applyProductOfferShipmentTypeFilters(
        SpyProductOfferShipmentTypeQuery $productOfferShipmentTypeQuery,
        ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
    ): SpyProductOfferShipmentTypeQuery {
        $productOfferShipmentTypeConditionsTransfer = $productOfferShipmentTypeCriteriaTransfer->getProductOfferShipmentTypeConditions();
        if (!$productOfferShipmentTypeConditionsTransfer) {
            return $productOfferShipmentTypeQuery;
        }

        if ($productOfferShipmentTypeConditionsTransfer->getProductOfferShipmentTypeIds() !== []) {
            $productOfferShipmentTypeQuery->filterByIdProductOfferShipmentType_In($productOfferShipmentTypeConditionsTransfer->getProductOfferShipmentTypeIds());
        }

        if ($productOfferShipmentTypeConditionsTransfer->getProductOfferIds() !== []) {
            $productOfferShipmentTypeQuery->filterByFkProductOffer_In($productOfferShipmentTypeConditionsTransfer->getProductOfferIds());
        }

        if ($productOfferShipmentTypeConditionsTransfer->getProductOfferReferences() !== []) {
            $productOfferShipmentTypeQuery->useProductOfferQuery()
                ->filterByProductOfferReference_In($productOfferShipmentTypeConditionsTransfer->getProductOfferReferences())
            ->endUse();
        }

        if ($productOfferShipmentTypeConditionsTransfer->getShipmentTypeIds() !== []) {
            $productOfferShipmentTypeQuery->filterByFkShipmentType_In($productOfferShipmentTypeConditionsTransfer->getShipmentTypeIds());
        }

        if ($productOfferShipmentTypeConditionsTransfer->getShipmentTypeNames() !== []) {
            $productOfferShipmentTypeQuery->useShipmentTypeQuery()
                ->filterByName_In($productOfferShipmentTypeConditionsTransfer->getShipmentTypeNames())
            ->endUse();
        }

        if ($productOfferShipmentTypeConditionsTransfer->getGroupByIdProductOffer()) {
            $productOfferShipmentTypeQuery
                ->select([SpyProductOfferShipmentTypeTableMap::COL_FK_PRODUCT_OFFER, SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE])
                ->withColumn(sprintf('GROUP_CONCAT(%s)', SpyProductOfferShipmentTypeTableMap::COL_FK_SHIPMENT_TYPE), static::SHIPMENT_TYPE_IDS_GROUPED)
                ->groupByFkProductOffer();
        }

        return $productOfferShipmentTypeQuery;
    }

    /**
     * @param \Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery $productOfferShipmentTypeQuery
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery
     */
    protected function applyProductOfferShipmentTypeSorting(
        SpyProductOfferShipmentTypeQuery $productOfferShipmentTypeQuery,
        ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
    ): SpyProductOfferShipmentTypeQuery {
        $sortTransfers = $productOfferShipmentTypeCriteriaTransfer->getSortCollection();
        foreach ($sortTransfers as $sortTransfer) {
            $productOfferShipmentTypeQuery->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $productOfferShipmentTypeQuery;
    }

    /**
     * @param \Orm\Zed\ProductOfferShipmentType\Persistence\SpyProductOfferShipmentTypeQuery $productOfferShipmentTypeQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyProductOfferShipmentTypePagination(
        SpyProductOfferShipmentTypeQuery $productOfferShipmentTypeQuery,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $paginationTransfer->setNbResults($productOfferShipmentTypeQuery->count());

            $productOfferShipmentTypeQuery
                ->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());

            return $productOfferShipmentTypeQuery;
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage() !== null) {
            $paginationModel = $productOfferShipmentTypeQuery->paginate(
                $paginationTransfer->getPageOrFail(),
                $paginationTransfer->getMaxPerPageOrFail(),
            );

            $paginationTransfer
                ->setNbResults($paginationModel->getNbResults())
                ->setFirstIndex($paginationModel->getFirstIndex())
                ->setLastIndex($paginationModel->getLastIndex())
                ->setFirstPage($paginationModel->getFirstPage())
                ->setLastPage($paginationModel->getLastPage())
                ->setNextPage($paginationModel->getNextPage())
                ->setPreviousPage($paginationModel->getPreviousPage());

            return $paginationModel->getQuery();
        }

        return $productOfferShipmentTypeQuery;
    }
}
