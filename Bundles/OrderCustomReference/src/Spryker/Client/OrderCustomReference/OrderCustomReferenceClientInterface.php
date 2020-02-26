<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OrderCustomReference;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface OrderCustomReferenceClientInterface
{
    /**
     * Specification:
     * - Sets Quote with QuoteTransfer::orderCustomReference.
     * - Makes Zed request.
     * - Saves order custom reference in persistence.
     * - Expects QuoteTransfer::idQuote to be provided.
     * - Expects QuoteTransfer::customer to be provided.
     * - Expects $orderCustomReference length to be less than 255 symbols.
     *
     * @api
     *
     * @param string $orderCustomReference
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setOrderCustomReference(
        string $orderCustomReference,
        QuoteTransfer $quoteTransfer
    ): QuoteResponseTransfer;
}
