<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCartsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteCollectionResponseTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;

interface QuoteCollectionResponseExpanderPluginInterface
{
    /**
     * Specification:
     * - Adds data to quote collection response transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteCollectionResponseTransfer $quoteCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionResponseTransfer
     */
    public function expandQuoteCollectionResponse(
        RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer,
        QuoteCollectionResponseTransfer $quoteCollectionResponseTransfer
    ): QuoteCollectionResponseTransfer;
}
