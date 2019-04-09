<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\QuoteApproval;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToQuoteClientInterface;
use Spryker\Client\QuoteApproval\Quote\QuoteStatusCheckerInterface;
use Spryker\Client\QuoteApproval\Zed\QuoteApprovalStubInterface;

class QuoteApprovalCreator implements QuoteApprovalCreatorInterface
{
    /**
     * @var \Spryker\Client\QuoteApproval\Quote\QuoteStatusCheckerInterface
     */
    protected $quoteStatusChecker;

    /**
     * @var \Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\QuoteApproval\Zed\QuoteApprovalStubInterface
     */
    protected $quoteApprovalStub;

    /**
     * @param \Spryker\Client\QuoteApproval\Quote\QuoteStatusCheckerInterface $quoteStatusChecker
     * @param \Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\QuoteApproval\Zed\QuoteApprovalStubInterface $quoteApprovalStub
     */
    public function __construct(
        QuoteStatusCheckerInterface $quoteStatusChecker,
        QuoteApprovalToQuoteClientInterface $quoteClient,
        QuoteApprovalStubInterface $quoteApprovalStub
    ) {
        $this->quoteStatusChecker = $quoteStatusChecker;
        $this->quoteClient = $quoteClient;
        $this->quoteApprovalStub = $quoteApprovalStub;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function createQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        if (!$this->quoteStatusChecker->isQuoteApplicableForApproval($this->quoteClient->getQuote())) {
            return (new QuoteApprovalResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage((new MessageTransfer())->setValue('quote_approval.create.quote_is_not_applicable_for_approval'));
        }

        return $this->quoteApprovalStub->createQuoteApproval($quoteApprovalRequestTransfer);
    }
}
