<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\TaxSetStorageTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStoragePersistenceFactory getFactory()
 */
class TaxStorageRepository extends AbstractRepository implements TaxStorageRepositoryInterface
{
    /**
     * finds to which tax sets assigned changed tax rates uses SpyTaxSetTaxQuery
     *
     * @param array $taxRateIds
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\TaxRateTransfer[]
     */
    public function findTaxSetIdsByTaxRateIds(array $taxRateIds): array
    {
        $taxRateIds = $this->getFactory()
            ->createTaxSetQuery()
            ->select(SpyTaxSetTableMap::COL_ID_TAX_SET)
            ->useSpyTaxSetTaxQuery()
            ->useSpyTaxRateQuery()
            ->filterByIdTaxRate_In($taxRateIds)
            ->endUse()
            ->endUse()
            ->groupBy(SpyTaxSetTableMap::COL_ID_TAX_SET)
            ->find()
            ->toArray();

        return $taxRateIds;
    }

    /**
     * @param array $taxSetIds
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\TaxSetTransfer[]
     */
    public function findTaxSetsByIds(array $taxSetIds): ArrayObject
    {
        $taxSetTransfers = new ArrayObject();
        $taxSets = $this->getFactory()
            ->createTaxSetQuery()
            ->filterByIdTaxSet($taxSetIds, Criteria::IN)
            ->find();

        $mapper = $this->getFactory()->createTaxStorageMapper();

        foreach ($taxSets as $taxSet) {
            $taxSetTransfers->append(
                $mapper->mapTaxSetEntityToTransfer($taxSet, new TaxSetTransfer())
            );
        }

        return $taxSetTransfers;
    }


    /**
     * @param array $taxSetIds
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\TaxSetStorageTransfer[]
     */
    public function findTaxSetStoragesByIds(array $taxSetIds): ArrayObject
    {
        $taxSetStorageTransfers = new ArrayObject();
        $taxSetStorages = $this->getFactory()
            ->createTaxSetStorageQuery()
            ->filterByFkTaxSet($taxSetIds, Criteria::IN)
            ->find();

        $mapper = $this->getFactory()->createTaxStorageMapper();

        foreach ($taxSetStorages as $taxSetStorage) {
            $taxSetStorageTransfers->append(
                $mapper->mapTaxSetStorageEntityToTransfer($taxSetStorage, new TaxSetStorageTransfer())
            );
        }

        return $taxSetStorageTransfers;
    }

    /**
     * @return \ArrayObject
     */
    public function findAllTaxSetSorageEntities(): ArrayObject
    {
        $taxSetStorageTransfers = new ArrayObject();
        $taxSetStorages = $this->getFactory()
            ->createTaxSetStorageQuery()
            ->find();

        $mapper = $this->getFactory()->createTaxStorageMapper();

        foreach ($taxSetStorages as $taxSetStorage) {
            $taxSetStorageTransfers->append(
                $mapper->mapTaxSetStorageEntityToTransfer($taxSetStorage, new TaxSetStorageTransfer())
            );
        }

        return $taxSetStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FileStorageTransfer $fileStorageTransfer
     *
     * @return void
     */
    public function saveFileStorage(TaxSetStorageTransfer $taxSetStorageTransfer): void
    {
        $fileStorageEntity = $this->getFactory()
            ->createTaxSetQuery()
            ->filterByIdTaxSet($taxSetStorageTransfer->getId())
            ->findOneOrCreate();

        $fileStorageEntity = $this->getFactory()
            ->createFileManagerStorageMapper()
            ->mapFileStorageTransferToEntity($fileStorageTransfer, $fileStorageEntity);

        $fileStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\FileStorageTransfer $fileStorageTransfer
     *
     * @return bool
     */
    public function deleteFileStorage(FileStorageTransfer $fileStorageTransfer): bool
    {
        $fileStorageEntity = $this->getFactory()
            ->createFileStorageQuery()
            ->filterByIdFileStorage($fileStorageTransfer->getIdFileStorage())
            ->findOne();

        if ($fileStorageEntity === null) {
            return false;
        }

        $fileStorageEntity->delete();

        return true;
    }
}
