<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use Generated\Shared\Transfer\AssigningGuestQuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;

interface QuoteUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\AssigningGuestQuoteRequestTransfer $assigningGuestQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function assignGuestCartToRegisteredCustomer(AssigningGuestQuoteRequestTransfer $assigningGuestQuoteRequestTransfer): QuoteResponseTransfer;
}
