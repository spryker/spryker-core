<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

use Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStoragePersistenceFactory getFactory()
 */
class TaxStorageEntityManager extends AbstractEntityManager implements TaxStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage $spyTaxSetStorage
     *
     * @return void
     */
    public function saveTaxSetStorage(SpyTaxSetStorage $spyTaxSetStorage): void
    {
        $spyTaxSetStorage->save();
    }

    /**
     * @param \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage $spyTaxSetStorage
     *
     * @return void
     */
    public function deleteTaxSetStorage(SpyTaxSetStorage $spyTaxSetStorage): void
    {
        $spyTaxSetStorage->delete();
    }
}
