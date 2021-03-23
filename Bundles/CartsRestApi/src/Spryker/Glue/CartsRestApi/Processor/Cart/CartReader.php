<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Cart;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCustomerClientInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartReader implements CartReaderInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface
     */
    protected $cartRestResponseBuilder;

    /**
     * @var \Spryker\Client\CartsRestApi\CartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @var \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CustomerExpanderPluginInterface[]
     */
    protected $customerExpanderPlugins;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CustomerExpanderPluginInterface[] $customerExpanderPlugins
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCustomerClientInterface $customerClient
     */
    public function __construct(
        CartRestResponseBuilderInterface $cartRestResponseBuilder,
        CartsRestApiClientInterface $cartsRestApiClient,
        array $customerExpanderPlugins,
        CartsRestApiToCustomerClientInterface $customerClient
    ) {
        $this->cartRestResponseBuilder = $cartRestResponseBuilder;
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->customerExpanderPlugins = $customerExpanderPlugins;
        $this->customerClient = $customerClient;
    }

    /**
     * @param string $uuidCart
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomerQuoteByUuid(string $uuidCart, RestRequestInterface $restRequest): RestResponseInterface
    {
        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($restRequest->getRestUser()->getSurrogateIdentifier())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier());

        $customerTransfer = $this->executeCustomerExpanderPlugins($customerTransfer, $restRequest);
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier())
            ->setCustomer($customerTransfer)
            ->setUuid($uuidCart);

        $quoteResponseTransfer = $this->cartsRestApiClient->findQuoteByUuid($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->cartRestResponseBuilder->createFailedErrorResponse($quoteResponseTransfer->getErrors());
        }

        return $this->cartRestResponseBuilder->createCartRestResponse(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restRequest->getMetadata()->getLocale()
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readCustomerCarts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $parentResources = $restRequest->getParentResources();
        if (!isset($parentResources['customers'])) {
            return $this->readCurrentCustomerCarts($restRequest);
        }

        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($parentResources['customers']->getId());

        $customer = $this->customerClient->findCustomerById($customerTransfer);
        if (!$customer) {
            return $this->cartRestResponseBuilder->createRestResponse();
        }

        $customer = $this->executeCustomerExpanderPlugins($customer, $restRequest);

        $quoteCriteriaFilter = $this->createQuoteCriteriaFilter(
            $customer->getCustomerReference(),
            $customer->getCompanyUserTransfer()->getIdCompanyUser()
        );

        $quoteCollectionTransfer = $this->cartsRestApiClient->getQuoteCollection($quoteCriteriaFilter);

        if (count($quoteCollectionTransfer->getQuotes()) === 0) {
            return $this->cartRestResponseBuilder->createRestResponse();
        }

        return $this->cartRestResponseBuilder->createRestQuoteCollectionResponse($quoteCollectionTransfer, $restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readCurrentCustomerCarts(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteCollectionTransfer = $this->getCustomerQuotes($restRequest);

        if (count($quoteCollectionTransfer->getQuotes()) === 0) {
            return $this->cartRestResponseBuilder->createRestResponse();
        }

        return $this->cartRestResponseBuilder->createRestQuoteCollectionResponse($quoteCollectionTransfer, $restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getCustomerQuotes(RestRequestInterface $restRequest): QuoteCollectionTransfer
    {
        $quoteCollectionTransfer = $this->cartsRestApiClient->getQuoteCollection(
            $this->createQuoteCriteriaFilter(
                $restRequest->getRestUser()->getNaturalIdentifier(),
                $restRequest->getRestUser()->getIdCompanyUser()
            )
        );

        return $quoteCollectionTransfer;
    }

    /**
     * @param string|null $customerReference
     * @param int|null $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer
     */
    protected function createQuoteCriteriaFilter(?string $customerReference, ?int $idCompanyUser): QuoteCriteriaFilterTransfer
    {
        return (new QuoteCriteriaFilterTransfer())
            ->setCustomerReference($customerReference)
            ->setIdCompanyUser($idCompanyUser);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function executeCustomerExpanderPlugins(CustomerTransfer $customerTransfer, RestRequestInterface $restRequest): CustomerTransfer
    {
        foreach ($this->customerExpanderPlugins as $customerExpanderPlugin) {
            $customerTransfer = $customerExpanderPlugin->expand($customerTransfer, $restRequest);
        }

        return $customerTransfer;
    }
}
