<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;

interface QuoteMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $registeredCustomer
     * @param \Generated\Shared\Transfer\QuoteCollectionResponseTransfer $quoteCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(
        CustomerTransfer $registeredCustomer,
        QuoteCollectionResponseTransfer $quoteCollectionResponseTransfer
    ): QuoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuoteResponseTransfer(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteUpdateRequestTransfer
     */
    public function mapQuoteTransferToQuoteUpdateRequestTransfer(
        QuoteTransfer $quoteTransfer
    ): QuoteUpdateRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $originalQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapQuoteTransferToOriginalQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        QuoteTransfer $originalQuoteTransfer
    ): QuoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionResponseTransfer
     */
    public function mapQuoteResponseErrorsToRestQuoteCollectionResponseErrors(
        QuoteResponseTransfer $quoteResponseTransfer
    ): QuoteCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function mapQuoteResponseErrorsToRestCodes(
        QuoteResponseTransfer $quoteResponseTransfer
    ): QuoteResponseTransfer;
}
