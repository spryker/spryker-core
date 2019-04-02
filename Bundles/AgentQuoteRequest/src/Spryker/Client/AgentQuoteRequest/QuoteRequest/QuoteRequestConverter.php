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
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS = 'quote_request.checkout.validation.error.wrong_status';

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
    public function convertQuoteRequestToQuote(QuoteRequestTransfer $quoteRequestTransfer): QuoteResponseTransfer
    {
        if (!$this->quoteRequestChecker->isQuoteRequestEditable($quoteRequestTransfer)) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS);
        }

        $quoteTransfer = $quoteRequestTransfer->getLatestVersion()->getQuote();

        $quoteTransfer
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference())
            ->setQuoteRequestVersionReference($quoteRequestTransfer->getLatestVersion()->getVersionReference())
            ->setName($quoteRequestTransfer->getQuoteRequestReference());

        $this->quoteClient->setQuote($quoteTransfer);

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(true)
            ->setQuoteTransfer($quoteTransfer);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function getErrorResponse(string $message): QuoteResponseTransfer
    {
        return (new QuoteResponseTransfer())
            ->setIsSuccessful(false)
            ->addError((new QuoteErrorTransfer())->setMessage($message));
    }
}
