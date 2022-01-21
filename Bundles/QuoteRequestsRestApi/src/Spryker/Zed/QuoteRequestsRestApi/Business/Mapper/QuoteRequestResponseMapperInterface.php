<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestsRestApi\Business\Mapper;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface QuoteRequestResponseMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function mapErrorMessagesFromQuoteResponseToQuoteRequestResponse(
        QuoteResponseTransfer $quoteResponseTransfer,
        QuoteRequestResponseTransfer $quoteRequestResponseTransfer
    ): QuoteRequestResponseTransfer;
}
