<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Persistence;

use DateTime;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveQuote(QuoteTransfer $quoteTransfer);

    /**
     * @param int $idQuote
     *
     * @return void
     */
    public function deleteQuoteById($idQuote);

    /**
     * @param \DateTime $lifetimeLimitDate
     *
     * @return void
     */
    public function deleteExpiredGuestQuotes(DateTime $lifetimeLimitDate): void;
}
