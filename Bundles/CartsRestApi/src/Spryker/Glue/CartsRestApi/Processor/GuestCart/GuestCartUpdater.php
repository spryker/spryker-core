<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\GuestCart;

use Generated\Shared\Transfer\AssigningGuestQuoteRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartsAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToSessionClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartUpdaterInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class GuestCartUpdater implements GuestCartUpdaterInterface
{
    protected const CUSTOMER_DATA = 'customer data';

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartReaderInterface
     */
    protected $guestCartReader;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Cart\CartUpdaterInterface
     */
    protected $cartUpdater;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface
     */
    protected $guestCartRestResponseBuilder;

    /**
     * @var \Spryker\Client\CartsRestApi\CartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToSessionClientInterface
     */
    protected $sessionClient;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartReaderInterface $guestCartReader
     * @param \Spryker\Glue\CartsRestApi\Processor\Cart\CartUpdaterInterface $cartUpdater
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToSessionClientInterface $sessionClient
     */
    public function __construct(
        GuestCartReaderInterface $guestCartReader,
        CartUpdaterInterface $cartUpdater,
        GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder,
        CartsRestApiClientInterface $cartsRestApiClient,
        CartsRestApiToSessionClientInterface $sessionClient
    ) {
        $this->guestCartReader = $guestCartReader;
        $this->cartUpdater = $cartUpdater;
        $this->guestCartRestResponseBuilder = $guestCartRestResponseBuilder;
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->sessionClient = $sessionClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function updateQuote(
        RestRequestInterface $restRequest,
        RestCartsAttributesTransfer $restCartsAttributesTransfer
    ): RestResponseInterface {
        return $this->cartUpdater->update($restRequest, $restCartsAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function updateGuestCartCustomerReferenceOnRegistration(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer|null $anonymousCustomerTransfer */
        $anonymousCustomerTransfer = $this->sessionClient->get(static::CUSTOMER_DATA);
        if ($anonymousCustomerTransfer === null || !($anonymousCustomerTransfer instanceof CustomerTransfer)) {
            return $customerTransfer;
        }

        $restQuoteCollectionResponseTransfer = $this->cartsRestApiClient->getCustomerQuoteCollection(
            (new RestQuoteCollectionRequestTransfer())->setCustomerReference($anonymousCustomerTransfer->getCustomerReference())
        );

        if (count($restQuoteCollectionResponseTransfer->getErrorCodes()) > 0) {
            return $customerTransfer;
        }

        $quoteCollection = $restQuoteCollectionResponseTransfer->getQuoteCollection();
        if ($quoteCollection === null) {
            return $customerTransfer;
        }

        $quotes = $quoteCollection->getQuotes();
        if ($quotes->count() === 0) {
            return $customerTransfer;
        }

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setUuid($quotes[0]->getUuid())
            ->setCustomer($customerTransfer);

        $restQuoteRequestTransfer = (new RestQuoteRequestTransfer())
            ->setCustomerReference($anonymousCustomerTransfer->getCustomerReference())
            ->setQuote($quoteTransfer)
            ->setQuoteUuid($quoteTransfer->getUuid());

        $this->cartsRestApiClient->updateQuote($restQuoteRequestTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function updateGuestCartCustomerReferenceOnCreate(
        RestRequestInterface $restRequest,
        CustomerTransfer $customerTransfer
    ): CustomerTransfer {
        if (!$restRequest->getUser()) {
            return $customerTransfer;
        }

        $assigningGuestQuoteRequestTransfer = (new AssigningGuestQuoteRequestTransfer())
            ->setAnonymousCustomerReference($restRequest->getUser()->getNaturalIdentifier())
            ->setCustomer($customerTransfer);

        $quoteResponseTransfer = $this->cartsRestApiClient->assignGuestCartToRegisteredCustomer($assigningGuestQuoteRequestTransfer);

        return $quoteResponseTransfer->getCustomer();
    }
}
