<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationCart\Reader;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductConfigurationInstanceQuoteReaderInterface
{
    /**
     * @param string $groupKey
     * @param string $sku
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceInQuote(
        string $groupKey,
        string $sku,
        QuoteTransfer $quoteTransfer
    ): ?ProductConfigurationInstanceTransfer;
}
