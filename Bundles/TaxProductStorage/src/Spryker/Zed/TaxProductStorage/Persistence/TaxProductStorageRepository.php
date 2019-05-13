<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Persistence;

use Generated\Shared\Transfer\TaxProductStorageTransfer;
use Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStoragePersistenceFactory getFactory()
 */
class TaxProductStorageRepository extends AbstractRepository implements TaxProductStorageRepositoryInterface
{
    /**
     * @module Product
     *
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\TaxProductStorageTransfer[]
     */
    public function getTaxProductTransferFromProductAbstractByIds(array $productAbstractIds): array
    {
        if (count($productAbstractIds) === 0) {
            return [];
        }
        $taxProductStorageMapper = $this->getFactory()->createTaxProductStorageMapper();
        $spyProductAbstracts = $this->getFactory()
            ->getProductAbstractQuery()
            ->filterByIdProductAbstract_In($productAbstractIds)
            ->find();

        if ($spyProductAbstracts->isEmpty()) {
            return [];
        }

        $taxProductStorageTransfers = [];
        foreach ($spyProductAbstracts as $spyProductAbstract) {
            $taxProductStorageTransfers[] = $taxProductStorageMapper->mapSpyProductAbstractToTaxProductStorageTransfer(
                $spyProductAbstract,
                new TaxProductStorageTransfer()
            );
        }

        return $taxProductStorageTransfers;
    }

    /**
     * @param int[] $productAbstractIds
     * @param string|null $keyColumn
     *
     * @return \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage[]
     */
    public function findTaxProductStorageEntitiesByProductAbstractIdsIndexedByKeyColumn(array $productAbstractIds, ?string $keyColumn = null): array
    {
        if (count($productAbstractIds) === 0) {
            return [];
        }

        $query = $this->getFactory()
            ->createTaxProductStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);

        return $query->find()
            ->getArrayCopy($keyColumn);
    }

    /**
     * @param int $productAbstractId
     *
     * @return \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage
     */
    public function findOrCreateTaxProductStorageByProductAbstractId(int $productAbstractId): SpyTaxProductStorage
    {
        return $this->getFactory()
            ->createTaxProductStorageQuery()
            ->filterByFkProductAbstract($productAbstractId)
            ->findOneOrCreate();
    }

    /**
     * @return \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage[]
     */
    public function findAllTaxProductStorageEntities(): array
    {
        $query = $this->getFactory()
            ->createTaxProductStorageQuery();

        return $query->find()->getArrayCopy();
    }
}
