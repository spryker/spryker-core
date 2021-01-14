<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestsRestApi\Business\Creator;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestsRequestTransfer;

interface QuoteRequestCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestsRequestTransfer $quoteRequestsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestsRequestTransfer $quoteRequestsRequestTransfer): QuoteRequestResponseTransfer;
}
