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
    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function deleteTaxProductStorageByProductAbstractIds(array $productAbstractIds): void
    {
        $this->getFactory()
            ->createTaxProductStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\TaxProductStorageTransfer[] $taxProductStorageTransfers
     *
     * @return void
     */
    public function updateTaxProductStorages(array $taxProductStorageTransfers): void
    {
        $spyTaxProductStorages = $this->findSpyTaxProductStoragesByProductAbstractIdsIndexedByProductAbstractIds(
            $this->getIdFromTransfers($taxProductStorageTransfers)
        );

        foreach ($taxProductStorageTransfers as $taxProductStorageTransfer) {
            $spyTaxProductStorage = $spyTaxProductStorages[$taxProductStorageTransfer->getIdProductAbstract()] ?? (new SpyTaxProductStorage())
                    ->setFkProductAbstract($taxProductStorageTransfer->getIdProductAbstract());
            $spyTaxProductStorage
                ->setSku($taxProductStorageTransfer->getSku())
                ->setData($taxProductStorageTransfer->toArray())
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
            ->getArrayCopy('idProductAbstract');
    }

    /**
     * @param \Generated\Shared\Transfer\TaxProductStorageTransfer[] $taxProductStorageTransfers
     *
     * @return int[]
     */
    protected function getIdFromTransfers(array $taxProductStorageTransfers): array
    {
        $ids = [];
        foreach ($taxProductStorageTransfers as $taxProductStorageTransfer) {
            $ids[] = $taxProductStorageTransfer->getIdProductAbstract();
        }

        return $ids;
    }
}
