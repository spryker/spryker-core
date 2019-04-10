<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\Plugin\QuoteApproval;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\QuoteApprovalExtension\Dependency\Plugin\QuoteApprovalCreatePreCheckPluginInterface;

class QuoteRequestQuoteApprovalCreatePreCheckPlugin extends AbstractClient implements QuoteApprovalCreatePreCheckPluginInterface
{
    /**
     * {@inheritdoc}
     * - Returns true if quote contains quote request reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(QuoteTransfer $quoteTransfer): bool
    {
        return !$quoteTransfer->getQuoteRequestReference();
    }
}
