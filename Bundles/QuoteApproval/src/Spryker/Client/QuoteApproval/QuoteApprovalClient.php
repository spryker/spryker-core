<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval;

use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;

/**
 * @method \Spryker\Client\QuoteApproval\QuoteApprovalFactory getFactory()
 */
class QuoteApprovalClient extends AbstractClient implements QuoteApprovalClientInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function approveQuote(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->getFactory()
            ->createQuoteApprovalStub()
            ->approveQuote($quoteApprovalRequestTransfer);
    }

    /**
     * @api
     *
     * @param int $idQuote
     *
     * @return string
     */
    public function getQuoteStatus(int $idQuote): string
    {
        return QuoteApprovalConfig::STATUS_WAITING; //todo: update with real functionality in PS-4362
    }
}
