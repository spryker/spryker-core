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
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class GuestCartItemAdder implements GuestCartItemAdderInterface
{
    /**
     * @var \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     */
    protected $cartsRestApiClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface
     */
    protected $guestCartRestResponseBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface
     */
    protected $cartRestResponseBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CartItemExpanderPluginInterface[]
     */
    protected $cartItemExpanderPlugins;

    /**
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     * @param \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CartItemExpanderPluginInterface[] $cartItemExpanderPlugins
     */
    public function __construct(
        CartsRestApiClientInterface $cartsRestApiClient,
        GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder,
        CartRestResponseBuilderInterface $cartRestResponseBuilder,
        array $cartItemExpanderPlugins
    ) {
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->guestCartRestResponseBuilder = $guestCartRestResponseBuilder;
        $this->cartRestResponseBuilder = $cartRestResponseBuilder;
        $this->cartItemExpanderPlugins = $cartItemExpanderPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addItemToGuestCart(
        RestRequestInterface $restRequest,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): RestResponseInterface {
        $cartItemRequestTransfer = $this->createCartItemRequestTransfer($restRequest, $restCartItemsAttributesTransfer);
        $quoteResponseTransfer = $this->cartsRestApiClient->addToGuestCart($cartItemRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->cartRestResponseBuilder->createFailedErrorResponse($quoteResponseTransfer->getErrors());
        }

        return $this->guestCartRestResponseBuilder->createGuestCartRestResponse(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restRequest->getMetadata()->getLocale()
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string|null
     */
    protected function findGuestCartIdentifier(RestRequestInterface $restRequest): ?string
    {
        $cartsResource = $restRequest->findParentResourceByType(CartsRestApiConfig::RESOURCE_GUEST_CARTS);
        if ($cartsResource) {
            return $cartsResource->getId();
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    protected function executeCartItemExpanderPlugins(
        CartItemRequestTransfer $cartItemRequestTransfer,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): CartItemRequestTransfer {
        foreach ($this->cartItemExpanderPlugins as $cartItemExpanderPlugin) {
            $cartItemRequestTransfer = $cartItemExpanderPlugin->expand(
                $cartItemRequestTransfer,
                $restCartItemsAttributesTransfer
            );
        }

        return $cartItemRequestTransfer;
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
        $cartItemRequestTransfer = (new CartItemRequestTransfer())
            ->setQuoteUuid($this->findGuestCartIdentifier($restRequest))
            ->setSku($restCartItemsAttributesTransfer->getSku())
            ->setQuantity($restCartItemsAttributesTransfer->getQuantity())
            ->setCustomer((new CustomerTransfer())->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier()));

        return $this->executeCartItemExpanderPlugins($cartItemRequestTransfer, $restCartItemsAttributesTransfer);
    }
}
