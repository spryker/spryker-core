<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Reader;

use Generated\Shared\Transfer\CurrencyCollectionTransfer;

interface CurrencyReaderInterface
{
    /**
     * @param list<string> $currencyCodes
     *
     * @return \Generated\Shared\Transfer\CurrencyCollectionTransfer
     */
    public function getCurrencyCollectionByCodes(array $currencyCodes): CurrencyCollectionTransfer;
}
