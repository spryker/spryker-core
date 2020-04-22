<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
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
     * @param string $labelName
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findProductLabelByNameProductLabel(string $labelName): ?ProductLabelTransfer
    {
        $productLabelEntity = $this->getFactory()
            ->createProductLabelQuery()
            ->filterByName($labelName)
            ->leftJoinWithProductLabelStore()
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
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function getAllProductLabelsSortedByPosition(): array
    {
        $productLabelEntities = $this->getFactory()
            ->createProductLabelQuery()
            ->leftJoinWithProductLabelStore()
            ->orderByPosition(Criteria::ASC)
            ->find();

        return $this->getFactory()
            ->createProductLabelMapper()
            ->mapProductLabelEntitiesToProductLabelTransfers($productLabelEntities);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function getProductLabelsByIdProductAbstract(int $idProductAbstract): array
    {
        $productLabelEntities = $this->getFactory()
            ->createProductLabelQuery()
            ->useSpyProductLabelProductAbstractQuery()
                ->filterByFkProductAbstract($idProductAbstract)
            ->endUse()
            ->leftJoinWithProductLabelStore()
            ->find();

        return $this->getFactory()
            ->createProductLabelMapper()
            ->mapProductLabelEntitiesToProductLabelTransfers($productLabelEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
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

        $productLabelEntities = $productLabelQuery->leftJoinSpyProductLabelLocalizedAttributes()
            ->filterByIsActive(true)
            ->filterByValidFrom('now', Criteria::LESS_EQUAL)
            ->_or()
            ->filterByValidFrom(null, Criteria::ISNULL)
            ->filterByValidTo('now', Criteria::GREATER_EQUAL)
            ->_or()
            ->filterByValidTo(null, Criteria::ISNULL)
            ->orderByIsExclusive(Criteria::DESC)
            ->orderByPosition(Criteria::ASC)
            ->groupByIdProductLabel()
            ->find();

        return $this->getFactory()
            ->createProductLabelMapper()
            ->mapProductLabelEntitiesToProductLabelTransfers($productLabelEntities);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
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
     * @return int[]
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
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[]
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

        return $this->getFactory()
            ->createProductLabelProductAbstractMapper()
            ->mapProductLabelProductAbstractEntitiesToProductLabelProductTransfers($productLabelProductAbstractEntities, []);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[]
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
     * @return \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[]
     */
    public function getProductLabelLocalizedAttributes(): array
    {
        $productLabelLocalizedAttributesEntities = $this->getFactory()
            ->createLocalizedAttributesQuery()
            ->joinWithSpyProductLabel()
            ->joinWithSpyLocale()
            ->find();

        if (!$productLabelLocalizedAttributesEntities->count()) {
            return [];
        }

        return $this->getFactory()
            ->createProductLabelLocalizedAttributesMapper()
            ->mapProductLabelLocalizedAttributesEntitiesToProductLabelLocalizedAttributesTransfers(
                $productLabelLocalizedAttributesEntities,
                []
            );
    }
}
