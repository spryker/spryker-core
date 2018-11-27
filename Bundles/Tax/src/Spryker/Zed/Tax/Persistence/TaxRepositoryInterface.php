<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Persistence;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;

interface TaxRepositoryInterface
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function isTaxSetNameUnique(string $name): bool;

    /**
     * @param string $name
     * @param int $idTaxSet
     *
     * @return bool
     */
    public function isTaxSetNameAndIdUnique(string $name, int $idTaxSet): bool;

    /**
     * @param int $idTaxRate
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer|null
     */
    public function findTaxRate(int $idTaxRate): ?TaxRateTransfer;

    /**
     * @param int $idTaxSet
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer|null
     */
    public function findTaxSet(int $idTaxSet): ?TaxSetTransfer;
}
