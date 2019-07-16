<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;

interface CurrencyRepositoryInterface
{
    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer|null
     */
    public function findCurrencyByIsoCode(string $isoCode): ?CurrencyTransfer;

    /**
     * @param string[] $isoCodes
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    public function getCurrencyTransfersByIsoCodes(array $isoCodes): array;
}
