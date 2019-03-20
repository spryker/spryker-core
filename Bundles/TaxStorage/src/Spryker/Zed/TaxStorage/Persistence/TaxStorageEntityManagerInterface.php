<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

use Generated\Shared\Transfer\TaxSetStorageTransfer;

interface TaxStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSetStorage $taxSetStorage
     *
     * @return void
     */
    public function saveTaxSetStorage(TaxSetStorageTransfer $taxSetStorage): void;

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSetStorage $taxSetStorage
     *
     * @return bool
     */
    public function deleteTaxSetStorage(TaxSetStorageTransfer $taxSetStorage): bool;
}
