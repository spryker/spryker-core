<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Dependency\Facade;

use Generated\Shared\Transfer\TaxSetCollectionTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;

interface ProductOptionToTaxFacadeInterface
{
    /**
     * @param int $grossPrice
     * @param float $taxRate
     *
     * @return float
     */
    public function getTaxAmountFromGrossPrice($grossPrice, $taxRate): float;

    /**
     * @return string
     */
    public function getDefaultTaxCountryIso2Code(): string;

    /**
     * @return float
     */
    public function getDefaultTaxRate(): float;

    /**
     * @return \Generated\Shared\Transfer\TaxSetCollectionTransfer
     */
    public function getTaxSets(): TaxSetCollectionTransfer;

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function getTaxSet($id): TaxSetTransfer;
}
