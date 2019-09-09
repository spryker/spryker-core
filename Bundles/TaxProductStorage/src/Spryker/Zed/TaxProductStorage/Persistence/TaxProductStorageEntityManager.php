<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Persistence;

use Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStoragePersistenceFactory getFactory()
 */
class TaxProductStorageEntityManager extends AbstractEntityManager implements TaxProductStorageEntityManagerInterface
{
    protected const COL_FK_PRODUCT_ABSTRACT = 'FkProductAbstract';

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function deleteTaxProductStoragesByProductAbstractIds(array $productAbstractIds): void
    {
        $spyTaxProductStorages = $this->findSpyTaxProductStoragesByProductAbstractIdsIndexedByProductAbstractIds($productAbstractIds);

        foreach ($spyTaxProductStorages as $spyTaxProductStorage) {
            $spyTaxProductStorage->setIsSendingToQueue(
                $this->getFactory()->getConfig()->isSendingToQueue()
            );
            $spyTaxProductStorage->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TaxProductStorageTransfer[] $taxProductStorageTransfers
     *
     * @return void
     */
    public function updateTaxProductStorages(array $taxProductStorageTransfers): void
    {
        $spyTaxProductStorages = $this->findSpyTaxProductStoragesByProductAbstractIdsIndexedByProductAbstractIds(
            $this->getIdsFromTransfers($taxProductStorageTransfers)
        );

        foreach ($taxProductStorageTransfers as $taxProductStorageTransfer) {
            $spyTaxProductStorage = $spyTaxProductStorages[$taxProductStorageTransfer->getIdProductAbstract()] ?? (new SpyTaxProductStorage())
                    ->setFkProductAbstract($taxProductStorageTransfer->getIdProductAbstract());
            $spyTaxProductStorage
                ->setSku($taxProductStorageTransfer->getSku())
                ->setData($taxProductStorageTransfer->toArray())
                ->setIsSendingToQueue(
                    $this->getFactory()->getConfig()->isSendingToQueue()
                )
                ->save();
        }
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage[]
     */
    protected function findSpyTaxProductStoragesByProductAbstractIdsIndexedByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->createTaxProductStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find()
            ->toKeyIndex(static::COL_FK_PRODUCT_ABSTRACT);
    }

    /**
     * @param \Generated\Shared\Transfer\TaxProductStorageTransfer[] $taxProductStorageTransfers
     *
     * @return int[]
     */
    protected function getIdsFromTransfers(array $taxProductStorageTransfers): array
    {
        $ids = [];
        foreach ($taxProductStorageTransfers as $taxProductStorageTransfer) {
            $ids[] = $taxProductStorageTransfer->getIdProductAbstract();
        }

        return $ids;
    }
}
