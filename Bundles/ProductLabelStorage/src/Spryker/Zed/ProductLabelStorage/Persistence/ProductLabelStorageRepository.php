<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Orm\Zed\ProductLabelStorage\Persistence\Map\SpyProductAbstractLabelStorageTableMap;
use Orm\Zed\ProductLabelStorage\Persistence\Map\SpyProductLabelDictionaryStorageTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Synchronization\Persistence\Propel\Formatter\SynchronizationDataTransferObjectFormatter;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStoragePersistenceFactory getFactory()
 */
class ProductLabelStorageRepository extends AbstractRepository implements ProductLabelStorageRepositoryInterface
{
    /**
     * @param int[] $productLabelIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductLabelIds(array $productLabelIds): array
    {
        return $this->getFactory()
            ->getProductLabelPropelQuery()
            ->filterByIdProductLabel_In($productLabelIds)
            ->joinSpyProductLabelProductAbstract()
            ->select(SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->distinct()
            ->find()
            ->getData();
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
     * @param int[] $productAbstractLabelStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getProductAbstractLabelStorageDataTransfersByIds(
        FilterTransfer $filterTransfer,
        array $productAbstractLabelStorageIds
    ): array {
        if (!$filterTransfer->getOrderBy()) {
            $filterTransfer->setOrderBy(SpyProductAbstractLabelStorageTableMap::COL_ID_PRODUCT_ABSTRACT_LABEL_STORAGE);
        }

        $query = $this->getFactory()->createSpyProductAbstractLabelStorageQuery();
        if ($productAbstractLabelStorageIds !== []) {
            $query->filterByIdProductAbstractLabelStorage_In($productAbstractLabelStorageIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productLabelDictionaryStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getProductLabelDictionaryStorageDataTransfersByIds(
        FilterTransfer $filterTransfer,
        array $productLabelDictionaryStorageIds
    ): array {
        if (!$filterTransfer->getOrderBy()) {
            $filterTransfer->setOrderBy(SpyProductLabelDictionaryStorageTableMap::COL_ID_PRODUCT_LABEL_DICTIONARY_STORAGE);
        }

        $query = $this->getFactory()->createSpyProductLabelDictionaryStorageQuery();
        if ($productLabelDictionaryStorageIds !== []) {
            $query->filterByIdProductLabelDictionaryStorage_In($productLabelDictionaryStorageIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(SynchronizationDataTransferObjectFormatter::class)
            ->find();
    }
}
