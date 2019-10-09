<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodesRestApi\Business;

use Generated\Shared\Transfer\QuoteTransfer;

interface CartCodesRestApiFacadeInterface
{
    /**
     * Specification:
     * - Extends QuoteTransfer with $code and its relevant data when the $code is applicable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, string $code): QuoteTransfer;
}
