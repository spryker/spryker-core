<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Currency;

use Generated\Shared\Transfer\CurrencyTransfer;

interface CurrencyReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getDefaultCurrencyTransfer(): CurrencyTransfer;

    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrencyTransferFromIsoCode(string $isoCode): CurrencyTransfer;

    /**
     * @param string[] $isoCodes
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    public function getCurrencyTransfersFromIsoCodes(array $isoCodes): array;
}
