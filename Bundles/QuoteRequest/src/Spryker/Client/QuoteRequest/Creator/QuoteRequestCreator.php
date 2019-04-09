<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\Creator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Client\QuoteRequest\Quote\QuoteRequestQuoteValidatorInterface;
use Spryker\Client\QuoteRequest\Zed\QuoteRequestStubInterface;

class QuoteRequestCreator implements QuoteRequestCreatorInterface
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @var \Spryker\Client\QuoteRequest\Zed\QuoteRequestStubInterface
     */
    protected $quoteRequestStub;

    /**
     * @var \Spryker\Client\QuoteRequest\Quote\QuoteRequestQuoteValidatorInterface
     */
    protected $quoteRequestQuoteValidator;

    /**
     * @param \Spryker\Client\QuoteRequest\Zed\QuoteRequestStubInterface $quoteRequestStub
     * @param \Spryker\Client\QuoteRequest\Quote\QuoteRequestQuoteValidatorInterface $quoteRequestQuoteValidator
     */
    public function __construct(
        QuoteRequestStubInterface $quoteRequestStub,
        QuoteRequestQuoteValidatorInterface $quoteRequestQuoteValidator
    ) {
        $this->quoteRequestStub = $quoteRequestStub;
        $this->quoteRequestQuoteValidator = $quoteRequestQuoteValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        $quoteTransfer = $quoteRequestTransfer->requireLatestVersion()
            ->getLatestVersion()
            ->getQuote();

        if (!$this->quoteRequestQuoteValidator->isQuoteApplicableForQuoteRequest($quoteTransfer)) {
            return (new QuoteRequestResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setValue(static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS)
                );
        }

        return $this->quoteRequestStub->createQuoteRequest($quoteRequestTransfer);
    }
}
