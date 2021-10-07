<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Persistence;

interface TaxProductStorageEntityManagerInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function deleteTaxProductStoragesByProductAbstractIds(array $productAbstractIds): void;

    /**
     * @param array<\Generated\Shared\Transfer\TaxProductStorageTransfer> $taxProductStorageTransfers
     *
     * @return void
     */
    public function updateTaxProductStorages(array $taxProductStorageTransfers): void;
}
