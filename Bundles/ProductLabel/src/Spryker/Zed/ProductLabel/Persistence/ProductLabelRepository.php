<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelPersistenceFactory getFactory()
 */
class ProductLabelRepository extends AbstractRepository implements ProductLabelRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findProductLabel(ProductLabelCriteriaTransfer $productLabelCriteriaTransfer): ?ProductLabelTransfer
    {
        $productLabelQuery = $this->getFactory()->createProductLabelQuery();
        $productLabelQuery = $this->applyProductLabelFilters($productLabelQuery, $productLabelCriteriaTransfer);
        $productLabelEntity = $productLabelQuery->findOne();

        if (!$productLabelEntity) {
            return null;
        }

        if ($productLabelCriteriaTransfer->getWithStores()) {
            $productLabelEntity->getProductLabelStores();
        }

        if ($productLabelCriteriaTransfer->getWithLocalizedAttributes()) {
            $productLabelEntity->getSpyProductLabelLocalizedAttributess();
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
            ->leftJoinWithSpyProductLabelLocalizedAttributes()
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
            ->leftJoinWithSpyProductLabelLocalizedAttributes()
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
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery $productLabelQuery
     * @param \Generated\Shared\Transfer\ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    protected function applyProductLabelFilters(
        SpyProductLabelQuery $productLabelQuery,
        ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
    ): SpyProductLabelQuery {
        if ($productLabelCriteriaTransfer->getIdProductLabel() !== null) {
            $productLabelQuery->filterByIdProductLabel(
                $productLabelCriteriaTransfer->getIdProductLabel()
            );
        }

        if ($productLabelCriteriaTransfer->getName() !== null) {
            $productLabelQuery->filterByName(
                $productLabelCriteriaTransfer->getName()
            );
        }

        return $productLabelQuery;
    }
}
