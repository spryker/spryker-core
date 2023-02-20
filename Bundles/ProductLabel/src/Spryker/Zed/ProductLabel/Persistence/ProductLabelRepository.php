<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductLabelCollectionTransfer;
use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelPersistenceFactory getFactory()
 */
class ProductLabelRepository extends AbstractRepository implements ProductLabelRepositoryInterface
{
    /**
     * @param int $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findProductLabelById(int $idProductLabel): ?ProductLabelTransfer
    {
        $productLabelEntity = $this->getFactory()
            ->createProductLabelQuery()
            ->filterByIdProductLabel($idProductLabel)
            ->leftJoinWithProductLabelStore()
            ->leftJoinWithSpyProductLabelLocalizedAttributes()
            ->find()
            ->getFirst();

        if (!$productLabelEntity) {
            return null;
        }

        return $this->getFactory()
            ->createProductLabelMapper()
            ->mapProductLabelEntityToProductLabelTransfer($productLabelEntity, new ProductLabelTransfer());
    }

    /**
     * @param string $productLabelName
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findProductLabelByName(string $productLabelName): ?ProductLabelTransfer
    {
        $productLabelEntity = $this->getFactory()
            ->createProductLabelQuery()
            ->filterByName($productLabelName)
            ->leftJoinWithProductLabelStore()
            ->leftJoinWithSpyProductLabelLocalizedAttributes()
            ->find()
            ->getFirst();

        if (!$productLabelEntity) {
            return null;
        }

        return $this->getFactory()
            ->createProductLabelMapper()
            ->mapProductLabelEntityToProductLabelTransfer($productLabelEntity, new ProductLabelTransfer());
    }

    /**
     * @return array<\Generated\Shared\Transfer\ProductLabelTransfer>
     */
    public function getAllProductLabelsSortedByPosition(): array
    {
        $productLabelEntities = $this->getFactory()
            ->createProductLabelQuery()
            ->leftJoinWithProductLabelStore()
            ->leftJoinWithSpyProductLabelLocalizedAttributes()
            ->orderByPosition(Criteria::ASC)
            ->find();

        return $this->getFactory()
            ->createProductLabelMapper()
            ->mapProductLabelEntitiesToProductLabelTransfers($productLabelEntities, []);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelTransfer>
     */
    public function getProductLabelsByIdProductAbstract(int $idProductAbstract): array
    {
        $productLabelEntities = $this->getFactory()
            ->createProductLabelQuery()
            ->leftJoinWithProductLabelStore()
            ->leftJoinWithSpyProductLabelLocalizedAttributes()
            ->useSpyProductLabelProductAbstractQuery()
                ->filterByFkProductAbstract($idProductAbstract)
            ->endUse()
            ->find();

        return $this->getFactory()
            ->createProductLabelMapper()
            ->mapProductLabelEntitiesToProductLabelTransfers($productLabelEntities, []);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelTransfer>
     */
    public function getActiveLabelsByCriteria(ProductLabelCriteriaTransfer $productLabelCriteriaTransfer): array
    {
        $productLabelQuery = $this->getFactory()->createProductLabelQuery();

        if ($productLabelCriteriaTransfer->getProductLabelIds()) {
            $productLabelQuery->filterByIdProductLabel_In($productLabelCriteriaTransfer->getProductLabelIds());
        }

        if ($productLabelCriteriaTransfer->getProductAbstractIds()) {
            $productLabelQuery->useSpyProductLabelProductAbstractQuery()
                    ->filterByFkProductAbstract_In($productLabelCriteriaTransfer->getProductAbstractIds())
                ->endUse();
        }

        if ($productLabelCriteriaTransfer->getStoreName() !== null) {
            $productLabelQuery->useProductLabelStoreQuery()
                    ->useStoreQuery()
                        ->filterByName($productLabelCriteriaTransfer->getStoreName())
                    ->endUse()
                ->endUse();
        }

        /** @var \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery $productLabelQuery */
        $productLabelQuery = $productLabelQuery->joinWithSpyProductLabelLocalizedAttributes(Criteria::LEFT_JOIN)
            ->useSpyProductLabelLocalizedAttributesQuery(null, Criteria::LEFT_JOIN)
                ->joinSpyLocale()
            ->endUse()
            ->filterByIsActive(true);

        $productLabelEntities = $productLabelQuery
            ->filterByValidFrom('now', Criteria::LESS_EQUAL)
            ->_or()
            ->filterByValidFrom(null, Criteria::ISNULL)
            ->filterByValidTo('now', Criteria::GREATER_EQUAL)
            ->_or()
            ->filterByValidTo(null, Criteria::ISNULL)
            ->orderByIsExclusive(Criteria::DESC)
            ->orderByPosition(Criteria::ASC)
            ->find();

        return $this->getFactory()
            ->createProductLabelMapper()
            ->mapProductLabelEntitiesToProductLabelTransfers($productLabelEntities, []);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function getProductLabelIdsByIdProductAbstract(int $idProductAbstract): array
    {
        $productLabelEntities = $this->getFactory()
            ->createProductLabelQuery()
            ->useSpyProductLabelProductAbstractQuery()
                ->filterByFkProductAbstract($idProductAbstract)
            ->endUse()
            ->select(SpyProductLabelTableMap::COL_ID_PRODUCT_LABEL)
            ->find();

        return $productLabelEntities->getData();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function getActiveProductLabelIdsByIdProductAbstract(int $idProductAbstract): array
    {
        $productLabelEntities = $this->getFactory()
            ->createProductLabelQuery()
            ->filterByIsActive(true)
            ->useSpyProductLabelProductAbstractQuery()
                ->filterByFkProductAbstract($idProductAbstract)
            ->endUse()
            ->select(SpyProductLabelTableMap::COL_ID_PRODUCT_LABEL)
            ->find();

        return $productLabelEntities->getData();
    }

    /**
     * @param int $idProductLabel
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelationByIdProductLabel(int $idProductLabel): StoreRelationTransfer
    {
        $productLabelStoreEntities = $this->getFactory()
            ->createProductLabelStoreQuery()
            ->filterByFkProductLabel($idProductLabel)
            ->leftJoinWithStore()
            ->find();

        $storeRelationTransfer = (new StoreRelationTransfer())->setIdEntity($idProductLabel);

        return $this->getFactory()
            ->createProductLabelStoreRelationMapper()
            ->mapProductLabelStoreEntitiesToStoreRelationTransfer($productLabelStoreEntities, $storeRelationTransfer);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer>
     */
    public function getProductLabelProductAbstractsByProductAbstractIds(array $productAbstractIds): array
    {
        $productLabelProductAbstractEntities = $this->getFactory()
            ->createProductRelationQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->joinWithSpyProductLabel()
            ->orderBy(SpyProductLabelTableMap::COL_POSITION)
            ->find();

        if (!$productLabelProductAbstractEntities->count()) {
            return [];
        }

        $productLabelProductAbstractTransfer = $this->getFactory()
            ->createProductLabelProductAbstractMapper()
            ->mapProductLabelProductAbstractEntitiesToProductLabelProductTransfers($productLabelProductAbstractEntities, []);

        return $this->mapProductLabelEntitiesToProductLabelTransfers(
            $productLabelProductAbstractEntities,
            $productLabelProductAbstractTransfer,
        );
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract> $productLabelProductAbstractEntities
     * @param array<\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer> $productLabelProductAbstractTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer>
     */
    protected function mapProductLabelEntitiesToProductLabelTransfers(
        ObjectCollection $productLabelProductAbstractEntities,
        array $productLabelProductAbstractTransfers
    ): array {
        foreach ($productLabelProductAbstractTransfers as $productLabelProductAbstractTransfer) {
            foreach ($productLabelProductAbstractEntities as $productLabelProductAbstractEntity) {
                if ($productLabelProductAbstractTransfer->getFkProductAbstract() === $productLabelProductAbstractEntity->getFkProductAbstract()) {
                    $productLabelTransfer = $this->getFactory()->createProductLabelMapper()->mapProductLabelEntityToProductLabelTransfer(
                        $productLabelProductAbstractEntity->getSpyProductLabel(),
                        new ProductLabelTransfer(),
                    );

                    $productLabelProductAbstractTransfer->setProductLabel($productLabelTransfer);
                }
            }
        }

        return $productLabelProductAbstractTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer>
     */
    public function getProductLabelProductAbstractsByFilter(FilterTransfer $filterTransfer): array
    {
        $productLabelProductAbstractEntities = $this->getFactory()
            ->createProductRelationQuery()
            ->setLimit($filterTransfer->getLimit())
            ->setOffset($filterTransfer->getOffset())
            ->find();

        if (!$productLabelProductAbstractEntities->count()) {
            return [];
        }

        return $this->getFactory()
            ->createProductLabelProductAbstractMapper()
            ->mapProductLabelProductAbstractEntitiesToProductLabelProductTransfers($productLabelProductAbstractEntities, []);
    }

    /**
     * @param int $idProductLabel
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer>
     */
    public function getProductAbstractRelationsByIdProductLabelAndProductAbstractIds(int $idProductLabel, array $productAbstractIds): array
    {
        $productLabelProductAbstractEntities = $this->getFactory()
            ->createProductRelationQuery()
            ->filterByFkProductLabel($idProductLabel)
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find();

        if (!$productLabelProductAbstractEntities->count()) {
            return [];
        }

        return $this->getFactory()
            ->createProductLabelProductAbstractMapper()
            ->mapProductLabelProductAbstractEntitiesToProductLabelProductTransfers($productLabelProductAbstractEntities, []);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function checkProductLabelProductAbstractByIdProductAbstractExists(int $idProductAbstract): bool
    {
        return $this->getFactory()
            ->createProductLabelQuery()
            ->useSpyProductLabelProductAbstractQuery()
                ->filterByFkProductAbstract($idProductAbstract)
            ->endUse()
            ->exists();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelCollectionTransfer
     */
    public function getProductLabelCollection(
        ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
    ): ProductLabelCollectionTransfer {
        $productLabelCollectionTransfer = new ProductLabelCollectionTransfer();
        $productLabelQuery = $this->getFactory()->createProductLabelQuery();

        $paginationTransfer = $productLabelCriteriaTransfer->getPagination();
        if ($paginationTransfer) {
            $productLabelQuery = $this->applyProductLabelPagination($productLabelQuery, $paginationTransfer);
            $productLabelCollectionTransfer->setPagination($paginationTransfer);
        }

        $productLabelEntities = $productLabelQuery->find();
        $productLabelEntitiesIndexedByProductLabelIds = $this->indexProductLabelEntitiesByProductLabelIds($productLabelEntities);

        $this->expandProductLabelWithProductLabelStores($productLabelEntitiesIndexedByProductLabelIds, $productLabelCriteriaTransfer);
        $this->expandProductLabelWithProductLabelLocalizedAttributes($productLabelEntitiesIndexedByProductLabelIds, $productLabelCriteriaTransfer);
        $this->expandProductLabelWithProductLabelProductAbstracts($productLabelEntitiesIndexedByProductLabelIds, $productLabelCriteriaTransfer);

        return $this->getFactory()
            ->createProductLabelMapper()
            ->mapProductLabelEntitiesToProductLabelCollectionTransfer(
                $productLabelEntities,
                $productLabelCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery $productLabelQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    protected function applyProductLabelPagination(
        SpyProductLabelQuery $productLabelQuery,
        PaginationTransfer $paginationTransfer
    ): SpyProductLabelQuery {
        $paginationTransfer->setNbResults($productLabelQuery->count());

        if ($paginationTransfer->getLimit() !== null && $paginationTransfer->getOffset() !== null) {
            return $productLabelQuery
                ->limit($paginationTransfer->getLimit())
                ->offset($paginationTransfer->getOffset());
        }

        return $productLabelQuery;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductLabel\Persistence\SpyProductLabel> $productLabelEntities
     *
     * @return array<int, \Orm\Zed\ProductLabel\Persistence\SpyProductLabel>
     */
    protected function indexProductLabelEntitiesByProductLabelIds(ObjectCollection $productLabelEntities): array
    {
        $productLabelEntitiesIndexedByProductLabelIds = [];
        foreach ($productLabelEntities as $productLabelEntity) {
            $productLabelEntitiesIndexedByProductLabelIds[$productLabelEntity->getIdProductLabel()] = $productLabelEntity;
        }

        return $productLabelEntitiesIndexedByProductLabelIds;
    }

    /**
     * @param array<int, \Orm\Zed\ProductLabel\Persistence\SpyProductLabel> $productLabelEntitiesIndexedByProductLabelIds
     * @param \Generated\Shared\Transfer\ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
     *
     * @return array<int, \Orm\Zed\ProductLabel\Persistence\SpyProductLabel>
     */
    protected function expandProductLabelWithProductLabelStores(
        array $productLabelEntitiesIndexedByProductLabelIds,
        ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
    ): array {
        foreach ($productLabelEntitiesIndexedByProductLabelIds as $productLabelEntity) {
            $productLabelEntity->initProductLabelStores(false);
        }

        if (!$productLabelCriteriaTransfer->getWithProductLabelStores()) {
            return $productLabelEntitiesIndexedByProductLabelIds;
        }

        $productLabelStoreEntities = $this->getFactory()
            ->createProductLabelStoreQuery()
            ->leftJoinWithStore()
            ->filterByFkProductLabel_In(array_keys($productLabelEntitiesIndexedByProductLabelIds))
            ->find();

        foreach ($productLabelStoreEntities as $productLabelStoreEntity) {
            $productLabelId = $productLabelStoreEntity->getFkProductLabel();
            if (!isset($productLabelEntitiesIndexedByProductLabelIds[$productLabelId])) {
                continue;
            }

            $productLabelEntitiesIndexedByProductLabelIds[$productLabelId]->addProductLabelStore($productLabelStoreEntity);
        }

        return $productLabelEntitiesIndexedByProductLabelIds;
    }

    /**
     * @param array<int, \Orm\Zed\ProductLabel\Persistence\SpyProductLabel> $productLabelEntitiesIndexedByProductLabelIds
     * @param \Generated\Shared\Transfer\ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
     *
     * @return array<int, \Orm\Zed\ProductLabel\Persistence\SpyProductLabel>
     */
    protected function expandProductLabelWithProductLabelLocalizedAttributes(
        array $productLabelEntitiesIndexedByProductLabelIds,
        ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
    ): array {
        foreach ($productLabelEntitiesIndexedByProductLabelIds as $productLabelEntity) {
            $productLabelEntity->initSpyProductLabelLocalizedAttributess(false);
        }

        if (!$productLabelCriteriaTransfer->getWithProductLabelLocalizedAttributes()) {
            return $productLabelEntitiesIndexedByProductLabelIds;
        }

        $productLabelLocalizedAttributeEntities = $this->getFactory()
            ->createLocalizedAttributesQuery()
            ->leftJoinWithSpyLocale()
            ->leftJoinWithSpyProductLabel()
            ->filterByFkProductLabel_In(array_keys($productLabelEntitiesIndexedByProductLabelIds))
            ->find();

        foreach ($productLabelLocalizedAttributeEntities as $productLabelLocalizedAttributeEntity) {
            $productLabelId = $productLabelLocalizedAttributeEntity->getFkProductLabel();
            if (!isset($productLabelEntitiesIndexedByProductLabelIds[$productLabelId])) {
                continue;
            }

            $productLabelEntitiesIndexedByProductLabelIds[$productLabelId]->addSpyProductLabelLocalizedAttributes($productLabelLocalizedAttributeEntity);
        }

        return $productLabelEntitiesIndexedByProductLabelIds;
    }

    /**
     * @param array<int, \Orm\Zed\ProductLabel\Persistence\SpyProductLabel> $productLabelEntitiesIndexedByProductLabelIds
     * @param \Generated\Shared\Transfer\ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
     *
     * @return array<int, \Orm\Zed\ProductLabel\Persistence\SpyProductLabel>
     */
    protected function expandProductLabelWithProductLabelProductAbstracts(
        array $productLabelEntitiesIndexedByProductLabelIds,
        ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
    ): array {
        foreach ($productLabelEntitiesIndexedByProductLabelIds as $productLabelEntity) {
            $productLabelEntity->initSpyProductLabelProductAbstracts(false);
        }

        if (!$productLabelCriteriaTransfer->getWithProductLabelProductAbstracts()) {
            return $productLabelEntitiesIndexedByProductLabelIds;
        }

        $productLabelProductAbstractEntities = $this->getFactory()
            ->createProductRelationQuery()
            ->filterByFkProductLabel_In(array_keys($productLabelEntitiesIndexedByProductLabelIds))
            ->find();

        foreach ($productLabelProductAbstractEntities as $productLabelProductAbstractEntity) {
            $productLabelId = $productLabelProductAbstractEntity->getFkProductLabel();
            if (!isset($productLabelEntitiesIndexedByProductLabelIds[$productLabelId])) {
                continue;
            }

            $productLabelEntitiesIndexedByProductLabelIds[$productLabelId]->addSpyProductLabelProductAbstract($productLabelProductAbstractEntity);
        }

        return $productLabelEntitiesIndexedByProductLabelIds;
    }
}
