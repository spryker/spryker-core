<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequestAgent\Dependency\Client;

use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;

interface QuoteRequestAgentToQuoteRequestClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    public function findQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): ?QuoteRequestTransfer;
}
