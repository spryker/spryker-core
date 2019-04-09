<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

use Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage;

interface TaxStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSetStorage $spyTaxSetStorage
     *
     * @return void
     */
    public function saveTaxSetStorage(SpyTaxSetStorage $spyTaxSetStorage): void;

    /**
     * @param \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage $spyTaxSetStorage
     *
     * @return void
     */
    public function deleteTaxSetStorage(SpyTaxSetStorage $spyTaxSetStorage): void;

//    /**
//     * @param int[] $taxSetIds
//     *
//     * @return void
//     */
//    public function deleteTaxSetStoragesByIds(array $taxSetIds): void;
}
