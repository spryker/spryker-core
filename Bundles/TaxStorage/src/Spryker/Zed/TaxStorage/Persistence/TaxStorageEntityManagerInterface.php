<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

interface TaxStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxSetStorageTransfer[] $taxSetStorageTransfers
     *
     * @return void
     */
    public function saveTaxSetStorage(array $taxSetStorageTransfers): void;

    /**
     * @param int[] $taxSetIds
     *
     * @return void
     */
    public function deleteTaxSetStoragesByIds(array $taxSetIds): void;
}
