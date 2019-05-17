<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxProductStorage;

use Generated\Shared\Transfer\TaxProductStorageTransfer;

interface TaxProductStorageClientInterface
{
    /**
     * Specification:
     *  - Finds a tax product within Storage with a given abstract product sku.
     *  - Returns null if tax product was not found.
     *
     * @api
     *
     * @param string $productAbstractSku
     *
     * @return \Generated\Shared\Transfer\TaxProductStorageTransfer|null
     */
    public function findTaxProductStorageByProductAbstractSku(string $productAbstractSku): ?TaxProductStorageTransfer;
}
