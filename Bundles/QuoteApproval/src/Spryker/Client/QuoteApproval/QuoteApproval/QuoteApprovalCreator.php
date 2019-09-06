<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\QuoteApproval;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Spryker\Client\QuoteApproval\Checker\QuoteApprovalCheckerInterface;
use Spryker\Client\QuoteApproval\Zed\QuoteApprovalStubInterface;

class QuoteApprovalCreator implements QuoteApprovalCreatorInterface
{
    protected const GLOSSARY_KEY_CART_CANT_BE_SENT_FOR_APPROVAL = 'quote_approval.create.cart_cant_be_sent_for_approval';

    /**
     * @var \Spryker\Client\QuoteApproval\Zed\QuoteApprovalStubInterface
     */
    protected $quoteApprovalStub;

    /**
     * @var \Spryker\Client\QuoteApproval\Checker\QuoteApprovalCheckerInterface
     */
    protected $quoteApprovalChecker;

    /**
     * @param \Spryker\Client\QuoteApproval\Zed\QuoteApprovalStubInterface $quoteApprovalStub
     * @param \Spryker\Client\QuoteApproval\Checker\QuoteApprovalCheckerInterface $quoteApprovalChecker
     */
    public function __construct(
        QuoteApprovalStubInterface $quoteApprovalStub,
        QuoteApprovalCheckerInterface $quoteApprovalChecker
    ) {
        $this->quoteApprovalStub = $quoteApprovalStub;
        $this->quoteApprovalChecker = $quoteApprovalChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function createQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        if (!$quoteApprovalRequestTransfer->getQuote()->getIdQuote()
            && $this->quoteApprovalChecker->isQuoteApplicableForApprovalProcess($quoteApprovalRequestTransfer->getQuote())
        ) {
            return (new QuoteApprovalResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage((new MessageTransfer())->setValue(static::GLOSSARY_KEY_CART_CANT_BE_SENT_FOR_APPROVAL));
        }

        return $this->quoteApprovalStub->createQuoteApproval($quoteApprovalRequestTransfer);
    }
}
