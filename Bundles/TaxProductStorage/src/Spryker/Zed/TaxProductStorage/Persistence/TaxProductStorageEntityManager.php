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
     * @param \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage $taxProductStorage
     *
     * @return void
     */
    public function saveTaxProductStorage(SpyTaxProductStorage $taxProductStorage): void
    {
        $taxProductStorage->save();
    }

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
}
