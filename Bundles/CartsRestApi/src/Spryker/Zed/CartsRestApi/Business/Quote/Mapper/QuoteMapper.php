<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteCollectionResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Zed\CartsRestApi\CartsRestApiConfig;

class QuoteMapper implements QuoteMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $registeredCustomer
     * @param \Generated\Shared\Transfer\QuoteCollectionResponseTransfer $quoteCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(
        CustomerTransfer $registeredCustomer,
        QuoteCollectionResponseTransfer $quoteCollectionResponseTransfer
    ): QuoteTransfer {
        $quoteCollection = $quoteCollectionResponseTransfer->getQuoteCollection();

        if (!$quoteCollection || $quoteCollection->getQuotes()->count() === 0) {
            return (new QuoteTransfer())->setCustomer($registeredCustomer);
        }

        $quoteTransfer = $quoteCollection->getQuotes()[0];
        $quoteTransfer->setCustomerReference($registeredCustomer->getCustomerReference());

        return $quoteTransfer->setCustomer($registeredCustomer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuoteResponseTransfer(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer
    {
        return (new QuoteResponseTransfer())->setCustomer(
            (new CustomerTransfer())->setCustomerReference($restQuoteRequestTransfer->getCustomerReference())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteUpdateRequestTransfer
     */
    public function mapQuoteTransferToQuoteUpdateRequestTransfer(QuoteTransfer $quoteTransfer): QuoteUpdateRequestTransfer
    {
        $quoteUpdateRequestTransfer = (new QuoteUpdateRequestTransfer())
            ->fromArray($quoteTransfer->modifiedToArray(), true);
        $quoteUpdateRequestAttributesTransfer = (new QuoteUpdateRequestAttributesTransfer())
            ->fromArray($quoteTransfer->modifiedToArray(), true);
        $quoteUpdateRequestTransfer->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);

        return $quoteUpdateRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $originalQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapQuoteTransferToOriginalQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        QuoteTransfer $originalQuoteTransfer
    ): QuoteTransfer {
        $originalQuoteTransfer->setCustomer($quoteTransfer->getCustomer());
        if ($quoteTransfer->getName()) {
            $originalQuoteTransfer->setName($quoteTransfer->getName());
        }

        if ($quoteTransfer->getCurrency()) {
            $originalQuoteTransfer->setCurrency($quoteTransfer->getCurrency());
        }

        if ($quoteTransfer->getPriceMode()) {
            $originalQuoteTransfer->setPriceMode($quoteTransfer->getPriceMode());
        }

        return $originalQuoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionResponseTransfer
     */
    public function mapQuoteResponseErrorsToRestQuoteCollectionResponseErrors(
        QuoteResponseTransfer $quoteResponseTransfer
    ): QuoteCollectionResponseTransfer {
        $errorCodes = [];
        foreach ($quoteResponseTransfer->getErrorCodes() as $error) {
            $errorCodes[] = $error;
        }

        $quoteResponseTransfer->setErrorCodes($errorCodes);

        return (new QuoteCollectionResponseTransfer())->setErrorCodes($errorCodes);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function mapQuoteResponseErrorsToRestCodes(
        QuoteResponseTransfer $quoteResponseTransfer
    ): QuoteResponseTransfer {
        $errorCodes = [];
        foreach ($quoteResponseTransfer->getErrors() as $error) {
            $errorCodes[] = isset($error[MessageTransfer::VALUE])
                ? CartsRestApiConfig::RESPONSE_ERROR_MAP[$error[MessageTransfer::VALUE]]
                : $error->getMessage();
        }

        $quoteResponseTransfer->setErrorCodes($errorCodes);

        return $quoteResponseTransfer;
    }
}
