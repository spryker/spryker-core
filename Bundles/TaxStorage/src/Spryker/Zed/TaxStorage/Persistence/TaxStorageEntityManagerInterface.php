<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

use Orm\Zed\Tax\Persistence\SpyTaxSetStorage;

interface TaxStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSetStorage $taxSetStorage
     */
    public function saveTaxSetStorage(SpyTaxSetStorage $taxSetStorage): void;

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSetStorage $taxSetStorage
     */
    public function deleteTaxSetStorage(SpyTaxSetStorage $taxSetStorage): void;
}