<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Zed;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
    public function createQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call(
            '/quote-approval/gateway/create-quote-approval',
            $quoteApprovalRequestTransfer
        );

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function removeQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer */
        $quoteApprovalResponseTransfer = $this->zedRequestClient->call(
            '/quote-approval/gateway/remove-quote-approval',
            $quoteApprovalRequestTransfer
        );

        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();

        return $quoteApprovalResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getQuoteApproverList(QuoteTransfer $quoteTransfer): CompanyUserCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUserCollectionTransfer $potentialQuoteApproversCollection */
        $potentialQuoteApproversCollection = $this->zedRequestClient->call(
            '/quote-approval/gateway/get-quote-approver-list',
            $quoteTransfer
        );

        return $potentialQuoteApproversCollection;
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
}
