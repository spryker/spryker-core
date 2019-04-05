<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\SourcePriceCleaner;

use Generated\Shared\Transfer\QuoteTransfer;

interface SourcePriceCleanerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function clearSourcePricesFromQuote(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
