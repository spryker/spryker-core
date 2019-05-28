<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct\DataReader;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PriceEnvironmentReaderInterface
{
    /**
     * @return string
     */
    public function getCurrentPriceMode(): string;

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrentCurrency(): CurrencyTransfer;

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getCurrentQuote(): QuoteTransfer;
}
