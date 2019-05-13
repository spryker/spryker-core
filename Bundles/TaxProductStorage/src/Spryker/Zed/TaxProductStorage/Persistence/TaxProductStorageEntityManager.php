<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Persistence;

use Generated\Shared\Transfer\TaxProductStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStoragePersistenceFactory getFactory()
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepositoryInterface getRepository()
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
     * @param \Generated\Shared\Transfer\TaxProductStorageTransfer $taxProductStorageTransfer
     *
     * @return void
     */
    public function updateTaxProductStorage(TaxProductStorageTransfer $taxProductStorageTransfer): void
    {
        $spyTaxProductStorage = $this->getRepository()
            ->findOrCreateTaxProductStorageByProductAbstractId(
                $taxProductStorageTransfer->getIdProductAbstract()
            );

        $spyTaxProductStorage
            ->setFkProductAbstract($taxProductStorageTransfer->getIdProductAbstract())
            ->setSku($taxProductStorageTransfer->getSku())
            ->setData($taxProductStorageTransfer->toArray())
            ->save();
    }
}
