<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business;

use Generated\Shared\Transfer\QuoteRequestTransfer;

interface QuoteRequestFacadeInterface
{
    /**
     * Specification:
     * - Creates new quote request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $shoppingListTransfer): QuoteRequestTransfer;
}
