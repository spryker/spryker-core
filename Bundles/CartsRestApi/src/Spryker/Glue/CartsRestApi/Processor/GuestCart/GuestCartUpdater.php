<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\GuestCart;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartUpdater;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class GuestCartUpdater extends CartUpdater implements GuestCartUpdaterInterface
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
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface $quoteClient
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface $persistentCartClient
     * @param \Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartReaderInterface $guestCartReader
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     */
    public function __construct(
        CartsRestApiToQuoteClientInterface $quoteClient,
        CartsRestApiToPersistentCartClientInterface $persistentCartClient,
        GuestCartReaderInterface $guestCartReader,
        CartsRestApiClientInterface $cartsRestApiClient,
        CartsResourceMapperInterface $cartsResourceMapper,
        CartRestResponseBuilderInterface $cartRestResponseBuilder
    ) {
        parent::__construct($cartsRestApiClient, $cartsResourceMapper, $cartRestResponseBuilder);

        $this->quoteClient = $quoteClient;
        $this->persistentCartClient = $persistentCartClient;
        $this->guestCartReader = $guestCartReader;
        $this->cartsRestApiClient = $cartsRestApiClient;
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
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setQuote($quoteTransfer)
            ->setQuoteUuid($restRequest->getResource()->getId());

        $this->cartsRestApiClient->updateQuote($restQuoteRequestTransfer);

        return $customerTransfer;
    }
}
