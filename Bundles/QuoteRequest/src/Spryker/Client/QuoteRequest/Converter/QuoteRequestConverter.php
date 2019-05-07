<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\Converter;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToCartClientInterface;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToPersistentCartClientInterface;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToQuoteClientInterface;
use Spryker\Client\QuoteRequest\Status\QuoteRequestStatusInterface;

class QuoteRequestConverter implements QuoteRequestConverterInterface
{
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS = 'quote_request.checkout.validation.error.wrong_status';
    protected const GLOSSARY_KEY_WRONG_CONVERT_QUOTE_REQUEST_VALID_UNTIL = 'quote_request.checkout.convert.error.wrong_valid_until';

    /**
     * @var \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToPersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @var \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Client\QuoteRequest\Status\QuoteRequestStatusInterface
     */
    protected $quoteRequestStatus;

    /**
     * @param \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToPersistentCartClientInterface $persistentCartClient
     * @param \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToCartClientInterface $cartClient
     * @param \Spryker\Client\QuoteRequest\Status\QuoteRequestStatusInterface $quoteRequestStatus
     */
    public function __construct(
        QuoteRequestToPersistentCartClientInterface $persistentCartClient,
        QuoteRequestToQuoteClientInterface $quoteClient,
        QuoteRequestToCartClientInterface $cartClient,
        QuoteRequestStatusInterface $quoteRequestStatus
    ) {
        $this->persistentCartClient = $persistentCartClient;
        $this->quoteClient = $quoteClient;
        $this->cartClient = $cartClient;
        $this->quoteRequestStatus = $quoteRequestStatus;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function convertQuoteRequestToLockedQuote(QuoteRequestTransfer $quoteRequestTransfer): QuoteResponseTransfer
    {
        if (!$this->quoteRequestStatus->isQuoteRequestReady($quoteRequestTransfer)) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS);
        }

        if ($quoteRequestTransfer->getValidUntil() && strtotime($quoteRequestTransfer->getValidUntil()) < time()) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_WRONG_CONVERT_QUOTE_REQUEST_VALID_UNTIL);
        }

        $latestQuoteRequestVersionTransfer = $quoteRequestTransfer->getLatestVersion();
        $quoteTransfer = $latestQuoteRequestVersionTransfer->getQuote();

        $quoteTransfer->setName($latestQuoteRequestVersionTransfer->getVersionReference());
        $quoteTransfer->setQuoteRequestVersionReference($latestQuoteRequestVersionTransfer->getVersionReference());

        $quoteTransfer = $this->cartClient->lockQuote($quoteTransfer);

        return $this->persistentCartClient->persistQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function convertQuoteRequestToQuote(QuoteRequestTransfer $quoteRequestTransfer): QuoteResponseTransfer
    {
        if (!$this->quoteRequestStatus->isQuoteRequestEditable($quoteRequestTransfer)) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS);
        }

        $quoteTransfer = $quoteRequestTransfer->getLatestVersion()->getQuote();

        $quoteTransfer
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference())
            ->setQuoteRequestVersionReference(null)
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
