<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\GuestCart;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Generated\Shared\Transfer\RestCartsAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartUpdaterInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class GuestCartUpdater implements GuestCartUpdaterInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface
     */
    protected $persistentCartClient;

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
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface $quoteClient
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface $persistentCartClient
     * @param \Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartReaderInterface $guestCartReader
     * @param \Spryker\Glue\CartsRestApi\Processor\Cart\CartUpdaterInterface $cartUpdater
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     */
    public function __construct(
        CartsRestApiToQuoteClientInterface $quoteClient,
        CartsRestApiToPersistentCartClientInterface $persistentCartClient,
        GuestCartReaderInterface $guestCartReader,
        CartUpdaterInterface $cartUpdater,
        GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder,
        CartsRestApiClientInterface $cartsRestApiClient
    ) {
        $this->quoteClient = $quoteClient;
        $this->persistentCartClient = $persistentCartClient;
        $this->guestCartReader = $guestCartReader;
        $this->cartUpdater = $cartUpdater;
        $this->guestCartRestResponseBuilder = $guestCartRestResponseBuilder;
        $this->cartsRestApiClient = $cartsRestApiClient;
    }

    public function updateQuote(
        RestRequestInterface $restRequest,
        RestCartsAttributesTransfer $restCartsAttributesTransfer
    ): RestResponseInterface {
        if (!$restRequest->getUser()) {
            return $this->guestCartRestResponseBuilder
                ->createAnonymousCustomerUniqueIdEmptyErrorRestResponse();
        }

        return $this->cartUpdater->updateQuote($restRequest, $restCartsAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function updateGuestCartCustomerReferenceOnRegistration(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();
        if (!$quoteTransfer->getIdQuote()) {
            return $customerTransfer;
        }

        $quoteUpdateRequestAttributesTransfer = (new QuoteUpdateRequestAttributesTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference());
        $quoteUpdateRequestTransfer = (new QuoteUpdateRequestTransfer())
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->setCustomer($customerTransfer)
            ->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);
        $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);

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

        $quoteTransfer = $this->guestCartReader->getCustomerQuote($restRequest);
        if (!$quoteTransfer) {
            return $customerTransfer;
        }

        $restQuoteRequestTransfer = (new RestQuoteRequestTransfer())
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier())
            ->setQuote(
                (new QuoteTransfer())->setCustomerReference(
                    $customerTransfer->getCustomerReference()
                )->setUuid($quoteTransfer->getUuid())
                ->setCustomer($customerTransfer)
            )
            ->setQuoteUuid($quoteTransfer->getUuid());

        $this->cartsRestApiClient->updateQuote($restQuoteRequestTransfer);

        return $customerTransfer;
    }
}
