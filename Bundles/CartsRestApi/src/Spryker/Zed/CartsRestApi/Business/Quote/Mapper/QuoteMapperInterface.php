<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote\Mapper;

use Generated\Shared\Transfer\QuoteCollectionResponseTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteCollectionResponseTransfer $quoteCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionResponseTransfer
     */
    public function mapQuoteCollectionTransferToRestShoppingListCollectionResponseTransfer(
        QuoteCollectionTransfer $quoteCollectionTransfer,
        QuoteCollectionResponseTransfer $quoteCollectionResponseTransfer
    ): QuoteCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapQuoteResponseTransferToQuoteTransfer(
        QuoteResponseTransfer $quoteResponseTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function mapQuoteResponseErrorsToRestCodes(
        QuoteResponseTransfer $quoteResponseTransfer
    ): QuoteResponseTransfer;
}
