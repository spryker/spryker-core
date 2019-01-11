<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Zed;

use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToZedRequestClientInterface;

class QuoteApprovalStub implements QuoteApprovalStubInterface
{
    /**
     * @var \Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(QuoteApprovalToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function approveQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer */
        $quoteApprovalResponseTransfer = $this->zedRequestClient->call(
            '/quote-approval/gateway/approve-quote-approval',
            $quoteApprovalRequestTransfer
        );

        return $quoteApprovalResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function declineQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer */
        $quoteApprovalResponseTransfer = $this->zedRequestClient->call(
            '/quote-approval/gateway/decline-quote-approval',
            $quoteApprovalRequestTransfer
        );

        return $quoteApprovalResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function cancelQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer */
        $quoteApprovalResponseTransfer = $this->zedRequestClient->call(
            '/quote-approval/gateway/cancel-quote-approval',
            $quoteApprovalRequestTransfer
        );

        return $quoteApprovalResponseTransfer;
    }
}
