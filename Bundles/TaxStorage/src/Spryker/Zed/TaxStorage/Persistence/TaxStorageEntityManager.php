<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

use Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage;
use Orm\Zed\Tax\Persistence\SpyTaxSetStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface;

/**
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStoragePersistenceFactory getFactory()
 */
class TaxStorageEntityManager extends AbstractEntityManager implements TaxStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSetStorage $taxSetStorage
     */
    public function saveTaxSetStorage(SpyTaxSetStorage $taxSetStorage): void
    {
        // TODO: Implement saveTaxSetStorage() method.
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSetStorage $taxSetStorage
     */
    public function deleteTaxSetStorage(SpyTaxSetStorage $taxSetStorage): void
    {
        // TODO: Implement deleteTaxSetStorage() method.
    }
}

