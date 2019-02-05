<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\AssigningGuestQuoteRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionResponseTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Zed\CartsRestApi\CartsRestApiConfig;

class QuoteMapper implements QuoteMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteUpdateRequestTransfer
     */
    public function mapQuoteTransferToQuoteUpdateRequestTransfer(
        QuoteTransfer $quoteTransfer
    ): QuoteUpdateRequestTransfer {
        return (new QuoteUpdateRequestTransfer())
            ->fromArray($quoteTransfer->modifiedToArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $originalQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapOriginalQuoteTransferToQuoteTransfer(
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer
     */
    public function mapQuoteTransferToQuoteUpdateRequestAttributesTransfer(
        QuoteTransfer $quoteTransfer
    ): QuoteUpdateRequestAttributesTransfer {
        return (new QuoteUpdateRequestAttributesTransfer())
            ->fromArray($quoteTransfer->modifiedToArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function mapRestQuoteCollectionRequestTransferToCustomerTransfer(
        RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
    ): CustomerTransfer {
        return (new CustomerTransfer())
            ->setCustomerReference($restQuoteCollectionRequestTransfer->getCustomerReference());
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function mapRestQuoteRequestTransferToCustomerTransfer(
        RestQuoteRequestTransfer $restQuoteRequestTransfer
    ): CustomerTransfer {
        return (new CustomerTransfer())
            ->setCustomerReference($restQuoteRequestTransfer->getCustomerReference());
    }

    /**
     * @param \Generated\Shared\Transfer\AssigningGuestQuoteRequestTransfer $assigningGuestQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer
     */
    public function mapAssigningGuestQuoteRequestTransferToRestQuoteCollectionRequestTransfer(
        AssigningGuestQuoteRequestTransfer $assigningGuestQuoteRequestTransfer
    ): RestQuoteCollectionRequestTransfer {
        return (new RestQuoteCollectionRequestTransfer())
            ->setCustomerReference($assigningGuestQuoteRequestTransfer->getAnonymousCustomerReference());
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
            $errorCodes[] = CartsRestApiConfig::RESPONSE_ERROR_MAP[$error] ?? $error;
        }

        $quoteResponseTransfer->setErrorCodes(new ArrayObject([$errorCodes]));

        return (new QuoteCollectionResponseTransfer())
            ->setQuoteCollection((new QuoteCollectionTransfer())->addQuote($quoteResponseTransfer->getQuoteTransfer()));
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
            $errorCodes[] = CartsRestApiConfig::RESPONSE_ERROR_MAP[$error['value']] ?? $error;
        }

        $quoteResponseTransfer->setErrorCodes($errorCodes);

        return $quoteResponseTransfer;
    }

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
        $quoteTransfer = $quoteCollectionResponseTransfer->getQuoteCollection()->getQuotes()[0];
        $quoteTransfer->setCustomerReference($registeredCustomer->getCustomerReference());

        return $quoteTransfer->setCustomer($registeredCustomer);
    }
}
