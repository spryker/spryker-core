<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\QuoteApproval;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Spryker\Client\QuoteApproval\Checker\QuoteCheckerInterface;
use Spryker\Client\QuoteApproval\Zed\QuoteApprovalStubInterface;

class QuoteApprovalCreator implements QuoteApprovalCreatorInterface
{
    protected const GLOSSARY_KEY_CART_CANT_BE_SENT_FOR_APPROVAL = 'quote_approval.create.cart_cant_be_sent_for_approval';

    /**
     * @var \Spryker\Client\QuoteApproval\Zed\QuoteApprovalStubInterface
     */
    protected $quoteApprovalStub;

    /**
     * @var \Spryker\Client\QuoteApproval\Checker\QuoteCheckerInterface
     */
    protected $quoteChecker;

    /**
     * @param \Spryker\Client\QuoteApproval\Zed\QuoteApprovalStubInterface $quoteApprovalStub
     * @param \Spryker\Client\QuoteApproval\Checker\QuoteCheckerInterface $quoteChecker
     */
    public function __construct(
        QuoteApprovalStubInterface $quoteApprovalStub,
        QuoteCheckerInterface $quoteChecker
    ) {
        $this->quoteApprovalStub = $quoteApprovalStub;
        $this->quoteChecker = $quoteChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function createQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        if (!$quoteApprovalRequestTransfer->getQuote()->getIdQuote()
            || !$this->quoteChecker->isQuoteApplicableForApprovalProcess($quoteApprovalRequestTransfer->getQuote())
        ) {
            return (new QuoteApprovalResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage((new MessageTransfer())->setValue(static::GLOSSARY_KEY_CART_CANT_BE_SENT_FOR_APPROVAL));
        }

        return $this->quoteApprovalStub->createQuoteApproval($quoteApprovalRequestTransfer);
    }
}
