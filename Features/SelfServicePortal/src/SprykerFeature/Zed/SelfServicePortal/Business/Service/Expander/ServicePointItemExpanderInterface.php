<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ServicePointItemExpanderInterface
{
    public function expandQuoteItemsWithServicePoint(QuoteTransfer $quoteTransfer): QuoteTransfer;

    public function expandCartItemsWithServicePoint(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;
}
