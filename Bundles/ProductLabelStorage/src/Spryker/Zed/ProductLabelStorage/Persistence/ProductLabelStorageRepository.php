<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Orm\Zed\ProductLabelStorage\Persistence\Map\SpyProductLabelDictionaryStorageTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;
use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStoragePersistenceFactory getFactory()
 */
class ProductLabelStorageRepository extends AbstractRepository implements ProductLabelStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function getUniqueProductAbstractIdsFromLocalizedAttributesByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryAllProductAbstractLocalizedAttributes()
            ->joinWithLocale()
            ->joinWithSpyProductAbstract()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->select(SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->distinct()
            ->find()
            ->getData();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[]
     */
    public function getProductLabelLocalizedAttributes(): array
    {
        $productLabelLocalizedAttributesEntities = $this->getFactory()
            ->getProductLabelQuery()
            ->queryAllLocalizedAttributesLabels()
            ->joinWithSpyLocale()
            ->joinWithSpyProductLabel()
            ->addAnd(SpyProductLabelTableMap::COL_IS_ACTIVE, true, Criteria::EQUAL)
            ->find();

        $productLabelLocalizedAttributesTransfers = [];

        foreach ($productLabelLocalizedAttributesEntities as $productLabelLocalizedAttributesEntity) {
            $productLabelLocalizedAttributesTransfers[] = $this->getFactory()
                ->createProductLabelLocalizedAttributesMapper()
                ->mapProductLabelLocalizedAttributesEntityToProductLabelLocalizedAttributesTransfer(
                    $productLabelLocalizedAttributesEntity,
                    new ProductLabelLocalizedAttributesTransfer()
                );
        }

        return $productLabelLocalizedAttributesTransfers;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductAbstractLabelStorageTransfersByProductAbstractIds(array $productAbstractIds): array
    {
        $productAbstractLabelStorageEntities = $this->getFactory()
            ->createSpyProductAbstractLabelStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find();

        if (!$productAbstractLabelStorageEntities->count()) {
            return [];
        }

        return $this->getFactory()
            ->createProductAbstractLabelStorageMapper()
            ->mapProductAbstractLabelStorageEntitiesToProductAbstractLabelStorageTransfers(
                $productAbstractLabelStorageEntities,
                []
            );
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[]
     */
    public function getProductLabelDictionaryStorageTransfers(): array
    {
        $productLabelDictionaryEntities = $this->getFactory()
            ->createSpyProductLabelDictionaryStorageQuery()
            ->find();

        if (!$productLabelDictionaryEntities->count()) {
            return [];
        }

        return $this->getFactory()
            ->createProductLabelDictionaryStorageMapper()
            ->mapProductLabelDictionaryStorageEntitiesToProductLabelDictionaryStorageTransfers(
                $productLabelDictionaryEntities,
                []
            );
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getProductAbstractLabelStorageDataTransfersByIds(FilterTransfer $filterTransfer, array $ids): array
    {
        if (!$filterTransfer->getOrderBy()) {
            $filterTransfer->setOrderBy(SpyProductLabelProductAbstractTableMap::COL_ID_PRODUCT_LABEL_PRODUCT_ABSTRACT);
        }

        $query = $this->getFactory()->createSpyProductAbstractLabelStorageQuery();
        if ($ids !== []) {
            $query->filterByIdProductAbstractLabelStorage_In($ids);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getProductLabelDictionaryStorageDataTransfersByIds(FilterTransfer $filterTransfer, array $ids): array
    {
        if (!$filterTransfer->getOrderBy()) {
            $filterTransfer->setOrderBy(SpyProductLabelDictionaryStorageTableMap::COL_ID_PRODUCT_LABEL_DICTIONARY_STORAGE);
        }

        $query = $this->getFactory()->createSpyProductLabelDictionaryStorageQuery();
        if ($ids !== []) {
            $query->filterByIdProductLabelDictionaryStorage_In($ids);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }
}
