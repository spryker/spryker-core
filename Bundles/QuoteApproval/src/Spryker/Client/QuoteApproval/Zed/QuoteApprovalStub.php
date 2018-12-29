<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Zed;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\QuoteApproveRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
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
     * @param \Generated\Shared\Transfer\QuoteApproveRequestTransfer $quoteApproveRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function sendApproveRequest(
        QuoteApproveRequestTransfer $quoteApproveRequestTransfer
    ): QuoteResponseTransfer {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call(
            '/quote-approval/gateway/send-quote-approve-request',
            $quoteApproveRequestTransfer
        );

        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getPotentialQuoteApproversList(QuoteTransfer $quoteTransfer): CompanyUserCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUserCollectionTransfer $potentialQuoteApproversCollection */
        $potentialQuoteApproversCollection = $this->zedRequestClient->call(
            '/quote-approval/gateway/get-potential-quote-approvers-list',
            $quoteTransfer
        );

        return $potentialQuoteApproversCollection;
    }
}
