<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteApprovalToQuoteClientBridge implements QuoteApprovalToQuoteClientInterface
{
    /**
     * @var \Spryker\Client\Quote\QuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\Quote\QuoteClientInterface $quoteClient
     */
    public function __construct($quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteLocked(QuoteTransfer $quoteTransfer): bool
    {
        return $this->quoteClient->isQuoteLocked($quoteTransfer);
    }
}
