<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\GuestCartItem;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class GuestCartItemUpdater implements GuestCartItemUpdaterInterface
{
    /**
     * @var \Spryker\Client\CartsRestApi\CartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface
     */
    protected $guestCartRestResponseBuilderInterface;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsMapperInterface
     */
    protected $cartsResourceMapper;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsMapperInterface
     */
    protected $cartItemsResourceMapper;

    /**
     * @var \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CustomerExpanderPluginInterface[]
     */
    protected $customerExpanderPlugins;

    /**
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsMapperInterface $cartsResourceMapper
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsMapperInterface $cartItemsResourceMapper
     * @param \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CustomerExpanderPluginInterface[] $customerExpanderPlugins
     */
    public function __construct(
        CartsRestApiClientInterface $cartsRestApiClient,
        GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder,
        CartsMapperInterface $cartsResourceMapper,
        CartItemsMapperInterface $cartItemsResourceMapper,
        array $customerExpanderPlugins
    ) {
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->guestCartRestResponseBuilderInterface = $guestCartRestResponseBuilder;
        $this->cartsResourceMapper = $cartsResourceMapper;
        $this->cartItemsResourceMapper = $cartItemsResourceMapper;
        $this->customerExpanderPlugins = $customerExpanderPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function updateItemQuantity(
        RestRequestInterface $restRequest,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): RestResponseInterface {
        $cartItemRequestTransfer = $this->createCartItemRequestTransfer($restRequest, $restCartItemsAttributesTransfer);

        $quoteResponseTransfer = $this->cartsRestApiClient->updateItemQuantity($cartItemRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->guestCartRestResponseBuilderInterface->createFailedErrorResponse($quoteResponseTransfer->getErrors());
        }

        return $this->guestCartRestResponseBuilderInterface->createGuestCartRestResponse($quoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string|null
     */
    protected function findCartIdentifier(RestRequestInterface $restRequest): ?string
    {
        $cartResource = $restRequest->findParentResourceByType(CartsRestApiConfig::RESOURCE_GUEST_CARTS);
        if ($cartResource) {
            return $cartResource->getId();
        }

        return null;
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

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    protected function createCartItemRequestTransfer(
        RestRequestInterface $restRequest,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): CartItemRequestTransfer {
        $uuidQuote = $this->findCartIdentifier($restRequest);
        $itemIdentifier = $restRequest->getResource()->getId();
        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($restRequest->getRestUser()->getSurrogateIdentifier())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier());
        $customerTransfer = $this->executeCustomerExpanderPlugins($customerTransfer, $restRequest);

        return (new CartItemRequestTransfer())
            ->setQuantity($restCartItemsAttributesTransfer->getQuantity())
            ->setQuoteUuid($uuidQuote)
            ->setSku($itemIdentifier)
            ->setCustomer($customerTransfer);
    }
}
