<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AgentQuoteRequest\QuoteRequest;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\AgentQuoteRequest\Dependency\Client\AgentQuoteRequestToQuoteClientInterface;

class QuoteRequestConverter implements QuoteRequestConverterInterface
{
    protected const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS = 'quote_request.checkout.validation.error.wrong_status';
    protected const MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND = 'quote_request.checkout.validation.error.version_not_found';

    /**
     * @var \Spryker\Client\AgentQuoteRequest\Dependency\Client\AgentQuoteRequestToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\AgentQuoteRequest\QuoteRequest\QuoteRequestCheckerInterface
     */
    protected $quoteRequestChecker;

    /**
     * @param \Spryker\Client\AgentQuoteRequest\Dependency\Client\AgentQuoteRequestToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\AgentQuoteRequest\QuoteRequest\QuoteRequestCheckerInterface $quoteRequestChecker
     */
    public function __construct(
        AgentQuoteRequestToQuoteClientInterface $quoteClient,
        QuoteRequestCheckerInterface $quoteRequestChecker
    ) {
        $this->quoteClient = $quoteClient;
        $this->quoteRequestChecker = $quoteRequestChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function convertQuoteRequestToEditableQuote(QuoteRequestTransfer $quoteRequestTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = (new QuoteResponseTransfer())->setIsSuccessful(false);

        if (!$this->quoteRequestChecker->isQuoteRequestEditable($quoteRequestTransfer)) {
            return $quoteResponseTransfer->addError((new QuoteErrorTransfer())->setMessage(static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_STATUS));
        }

        if (!$quoteRequestTransfer->getQuoteInProgress()) {
            return $quoteResponseTransfer->addError((new QuoteErrorTransfer())->setMessage(static::MESSAGE_ERROR_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND));
        }

        $quoteTransfer = $quoteRequestTransfer->getQuoteInProgress();
        $quoteTransfer->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        $currentQuoteTransfer = $this->quoteClient->getQuote();

        $quoteTransfer
            ->setCustomer($currentQuoteTransfer->getCustomer())
            ->setCustomerReference($currentQuoteTransfer->getCustomerReference())
            ->setStore($currentQuoteTransfer->getStore());

        $this->quoteClient->setQuote($quoteTransfer);

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(true)
            ->setQuoteTransfer($quoteTransfer);
    }
}
