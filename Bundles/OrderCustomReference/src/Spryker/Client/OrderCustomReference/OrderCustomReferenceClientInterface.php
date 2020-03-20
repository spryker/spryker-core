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
     * - Sets QuoteTransfer::orderCustomReference.
     * - Makes Zed request.
     * - Saves order custom reference in Persistence.
     * - Expects QuoteTransfer::idQuote to be provided.
     * - Expects QuoteTransfer::customer to be provided.
     * - Validates the length of $orderCustomReference if is less than Config::getOrderCustomReferenceMaxLength().
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
