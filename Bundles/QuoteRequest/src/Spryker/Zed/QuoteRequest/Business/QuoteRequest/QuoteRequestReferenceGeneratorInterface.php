<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use Generated\Shared\Transfer\QuoteRequestTransfer;

interface QuoteRequestReferenceGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return string
     */
    public function generateQuoteRequestReference(QuoteRequestTransfer $quoteRequestTransfer): string;
}
