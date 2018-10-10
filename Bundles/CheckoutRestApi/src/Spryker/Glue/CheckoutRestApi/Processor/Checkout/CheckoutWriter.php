<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Checkout;

use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToQuoteClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CheckoutWriter implements CheckoutWriterInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface
     */
    protected $checkoutClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface $checkoutClient
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToQuoteClientInterface $quoteClient
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface $cartClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CheckoutRestApiToCheckoutClientInterface $checkoutClient,
        CheckoutRestApiToQuoteClientInterface $quoteClient,
        CheckoutRestApiToCartClientInterface $cartClient
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->checkoutClient = $checkoutClient;
        $this->quoteClient = $quoteClient;
        $this->cartClient = $cartClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function placeOrder(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }
}
