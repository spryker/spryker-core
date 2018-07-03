<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Persistence;

interface TaxRepositoryInterface
{
    /**
     * @param string $name
     * @param int|null $idTaxSet
     *
     * @return bool
     */
    public function isTaxSetNameUnique(string $name, ?int $idTaxSet = null): bool;
}
