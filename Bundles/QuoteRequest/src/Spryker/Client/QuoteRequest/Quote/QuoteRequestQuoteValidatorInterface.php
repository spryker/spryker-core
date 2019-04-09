<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\Quote;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteRequestQuoteValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteApplicableForQuoteRequest(QuoteTransfer $quoteTransfer): bool;
}
