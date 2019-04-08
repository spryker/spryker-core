<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\Validator;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\QuoteRequest\Business\Reader\QuoteRequestReaderInterface;

class QuoteRequestTimeValidator implements QuoteRequestTimeValidatorInterface
{
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND = 'quote_request.checkout.validation.error.version_not_found';
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_NOT_FOUND = 'quote_request.checkout.validation.error.not_found';
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS = 'quote_request.checkout.validation.error.wrong_status';
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VERSION = 'quote_request.checkout.validation.error.wrong_version';
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VALID_UNTIL = 'quote_request.checkout.validation.error.wrong_valid_until';

    /**
     * @var \Spryker\Zed\QuoteRequest\Business\Reader\QuoteRequestReaderInterface
     */
    protected $quoteRequestReader;

    /**
     * @param \Spryker\Zed\QuoteRequest\Business\Reader\QuoteRequestReaderInterface $quoteRequestReader
     */
    public function __construct(QuoteRequestReaderInterface $quoteRequestReader)
    {
        $this->quoteRequestReader = $quoteRequestReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkValidUntil(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        if (!$quoteTransfer->getQuoteRequestVersionReference()) {
            return true;
        }

        $quoteRequestVersionTransfer = $this->findQuoteRequestVersion($quoteTransfer);

        if (!$quoteRequestVersionTransfer) {
            $this->addCheckoutError($checkoutResponseTransfer, static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VERSION_NOT_FOUND);

            return false;
        }

        $quoteRequestTransfer = $this->findQuoteRequest($quoteRequestVersionTransfer);

        if (!$quoteRequestTransfer) {
            $this->addCheckoutError($checkoutResponseTransfer, static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_NOT_FOUND);

            return false;
        }

        return $this->isQuoteRequestValid($quoteRequestTransfer, $quoteRequestVersionTransfer, $checkoutResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer|null
     */
    protected function findQuoteRequestVersion(QuoteTransfer $quoteTransfer): ?QuoteRequestVersionTransfer
    {
        $quoteTransfer->requireQuoteRequestVersionReference();

        $quoteRequestVersionFilterTransfer = (new QuoteRequestVersionFilterTransfer())
            ->setQuoteRequestVersionReference($quoteTransfer->getQuoteRequestVersionReference());

        $quoteRequestVersionTransfers = $this->quoteRequestReader
            ->getQuoteRequestVersionCollectionByFilter($quoteRequestVersionFilterTransfer)
            ->getQuoteRequestVersions()
            ->getArrayCopy();

        return array_shift($quoteRequestVersionTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    protected function findQuoteRequest(QuoteRequestVersionTransfer $quoteRequestVersionTransfer): ?QuoteRequestTransfer
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setIdQuoteRequest($quoteRequestVersionTransfer->getFkQuoteRequest());

        $quoteRequestTransfers = $this->quoteRequestReader
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        return array_shift($quoteRequestTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    protected function isQuoteRequestValid(
        QuoteRequestTransfer $quoteRequestTransfer,
        QuoteRequestVersionTransfer $quoteRequestVersionTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool {
        if ($quoteRequestTransfer->getStatus() !== SharedQuoteRequestConfig::STATUS_READY) {
            $this->addCheckoutError($checkoutResponseTransfer, static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_STATUS);

            return false;
        }

        if ($quoteRequestTransfer->getLatestVersion()->getIdQuoteRequestVersion() !== $quoteRequestVersionTransfer->getIdQuoteRequestVersion()) {
            $this->addCheckoutError($checkoutResponseTransfer, static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VERSION);

            return false;
        }

        if ($quoteRequestTransfer->getValidUntil() && strtotime($quoteRequestTransfer->getValidUntil()) < time()) {
            $this->addCheckoutError($checkoutResponseTransfer, static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VALID_UNTIL);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function addCheckoutError(CheckoutResponseTransfer $checkoutResponseTransfer, string $message): CheckoutResponseTransfer
    {
        $checkoutResponseTransfer
            ->addError((new CheckoutErrorTransfer())->setMessage($message))
            ->setIsSuccess(false);

        return $checkoutResponseTransfer;
    }
}
