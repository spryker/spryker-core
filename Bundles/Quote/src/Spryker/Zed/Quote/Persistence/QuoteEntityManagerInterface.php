<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Persistence;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string[] $quoteFieldsAllowedForSaving
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveQuote(QuoteTransfer $quoteTransfer, array $quoteFieldsAllowedForSaving);

    /**
     * @param int $idQuote
     *
     * @return void
     */
    public function deleteQuoteById($idQuote);
}
