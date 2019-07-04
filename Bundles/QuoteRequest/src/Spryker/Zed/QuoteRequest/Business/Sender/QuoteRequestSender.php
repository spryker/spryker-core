<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\Sender;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\QuoteRequest\Business\Reader\QuoteRequestReaderInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface;

class QuoteRequestSender implements QuoteRequestSenderInterface
{
    protected const GLOSSARY_KEY_CONCURRENT_CUSTOMERS = 'quote_request.update.validation.concurrent';
    protected const GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';
    protected const GLOSSARY_KEY_QUOTE_REQUEST_EMPTY_QUOTE_ITEMS = 'quote_request.validation.error.empty_quote_items';

    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface
     */
    protected $quoteRequestEntityManager;

    /**
     * @var \Spryker\Zed\QuoteRequest\Business\Reader\QuoteRequestReaderInterface
     */
    protected $quoteRequestReader;

    /**
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface $quoteRequestEntityManager
     * @param \Spryker\Zed\QuoteRequest\Business\Reader\QuoteRequestReaderInterface $quoteRequestReader
     */
    public function __construct(
        QuoteRequestEntityManagerInterface $quoteRequestEntityManager,
        QuoteRequestReaderInterface $quoteRequestReader
    ) {
        $this->quoteRequestEntityManager = $quoteRequestEntityManager;
        $this->quoteRequestReader = $quoteRequestReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function sendQuoteRequestToUser(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestFilterTransfer->requireIdCompanyUser();
        $quoteRequestResponseTransfer = $this->quoteRequestReader->getQuoteRequest($quoteRequestFilterTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $quoteRequestResponseTransfer;
        }

        $quoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();
        $quoteRequestResponseTransfer = $this->validateQuoteRequestBeforeSend($quoteRequestTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $quoteRequestResponseTransfer;
        }

        if (!$this->isQuoteRequestNonConcurrent($quoteRequestTransfer)) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_CONCURRENT_CUSTOMERS);
        }

        $quoteRequestTransfer->setStatus(SharedQuoteRequestConfig::STATUS_WAITING);
        $quoteRequestTransfer = $this->quoteRequestEntityManager->updateQuoteRequest($quoteRequestTransfer);

        return $quoteRequestResponseTransfer->setQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function validateQuoteRequestBeforeSend(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        if ($quoteRequestTransfer->getStatus() !== SharedQuoteRequestConfig::STATUS_DRAFT) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS);
        }

        if (!$quoteRequestTransfer->getLatestVersion()->getQuote()->getItems()->count()) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_EMPTY_QUOTE_ITEMS);
        }

        return (new QuoteRequestResponseTransfer())
            ->setIsSuccessful(true);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function getErrorResponse(string $message): QuoteRequestResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new QuoteRequestResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    protected function isQuoteRequestNonConcurrent(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->quoteRequestEntityManager->updateQuoteRequestStatus(
            $quoteRequestTransfer->getQuoteRequestReference(),
            $quoteRequestTransfer->getStatus(),
            SharedQuoteRequestConfig::STATUS_WAITING
        );
    }
}
