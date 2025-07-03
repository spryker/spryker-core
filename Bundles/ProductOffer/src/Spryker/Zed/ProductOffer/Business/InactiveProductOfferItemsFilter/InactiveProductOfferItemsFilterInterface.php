<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\InactiveProductOfferItemsFilter;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface InactiveProductOfferItemsFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param list<string> $itemProductOfferReferencesToSkipFiltering
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterInactiveProductOfferItems(QuoteTransfer $quoteTransfer, array $itemProductOfferReferencesToSkipFiltering = []): QuoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function filterOutInactiveCartChangeProductOfferItems(
        CartChangeTransfer $cartChangeTransfer
    ): CartChangeTransfer;
}
