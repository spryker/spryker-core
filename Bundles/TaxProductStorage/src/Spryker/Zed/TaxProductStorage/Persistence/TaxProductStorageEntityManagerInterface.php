<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Persistence;

use Generated\Shared\Transfer\TaxProductStorageTransfer;

interface TaxProductStorageEntityManagerInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function deleteTaxProductStorageByProductAbstractIds(array $productAbstractIds): void;

    /**
     * @param \Generated\Shared\Transfer\TaxProductStorageTransfer $taxProductStorageTransfer
     *
     * @return void
     */
    public function updateTaxProductStorage(TaxProductStorageTransfer $taxProductStorageTransfer): void;
}
