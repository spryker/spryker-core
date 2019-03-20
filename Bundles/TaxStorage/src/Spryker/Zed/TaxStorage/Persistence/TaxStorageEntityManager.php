<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

use Generated\Shared\Transfer\TaxSetStorageTransfer;
use Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStoragePersistenceFactory getFactory()
 */
class TaxStorageEntityManager extends AbstractEntityManager implements TaxStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage $taxSetStorage
     *
     * @return void
     */
    public function saveTaxSetStorage(TaxSetStorageTransfer $taxSetStorage): void
    {
        $taxStorageEntity = $this->getFactory()
            ->createTaxSetStorageQuery()
            ->filterByFkTaxSet($taxSetStorage->getFkTaxSet())
            ->findOneOrCreate();

        $taxStorageEntity = $this->getFactory()
            ->createTaxStorageMapper()
            ->mapTaxSetStorageTransferToEntity($taxSetStorage, $taxStorageEntity);

        $taxStorageEntity->save();
    }

    /**
     * @param \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage $taxSetStorage
     *
     * @return bool
     */
    public function deleteTaxSetStorage(TaxSetStorageTransfer $taxSetStorage): bool
    {
        $storageEntity = $this->getFactory()
            ->createTaxSetStorageQuery()
            ->filterByFkTaxSet($taxSetStorage->getFkTaxSet())
            ->findOne();
        if ($storageEntity instanceof SpyTaxSetStorage) {
            $storageEntity->delete();
            return true;
        }

        return false;
    }
}
