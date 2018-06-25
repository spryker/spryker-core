<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartProductListConnector\Business;

use Generated\Shared\Transfer\QuoteTransfer;

interface CartProductListConnectorFacadeInterface
{
    /**
     * Specification:
     *  - Removes restricted items from quote.
     *  - Adds note to messages about removed items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterRestrictedItems(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
