<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\GuestCart;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class GuestCartCreator implements GuestCartCreatorInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface
     */
    protected $cartsResourceMapper;

    /**
     * @var \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface
     */
    protected $quoteCreatorPlugin;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface
     */
    protected $guestCartRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     * @param \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface $quoteCreatorPlugin
     */
    public function __construct(
        GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder,
        CartsResourceMapperInterface $cartsResourceMapper,
        QuoteCreatorPluginInterface $quoteCreatorPlugin
    ) {
        $this->guestCartRestResponseBuilder = $guestCartRestResponseBuilder;
        $this->cartsResourceMapper = $cartsResourceMapper;
        $this->quoteCreatorPlugin = $quoteCreatorPlugin;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function create(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteResponseTransfer = $this->createQuote($restRequest);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->guestCartRestResponseBuilder->createFailedCreatingCartErrorResponse();
        }

        return $this->guestCartRestResponseBuilder->createGuestCartRestResponse($quoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(RestRequestInterface $restRequest): QuoteResponseTransfer
    {
        return $this->quoteCreatorPlugin->createQuote($restRequest, $this->createQuoteTransfer($restRequest));
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(RestRequestInterface $restRequest): QuoteTransfer
    {
        $customerTransfer = $this->getCustomerTransfer($restRequest);

        return (new QuoteTransfer())->setCustomer($customerTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerTransfer(RestRequestInterface $restRequest): CustomerTransfer
    {
        return (new CustomerTransfer())->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());
    }
}
