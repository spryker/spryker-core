<?php

namespace Spryker\CartCodesRestApi\src\Spryker\Glue\CartCodesRestApi\Processor\CartCodeAdder;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestDiscountsRequestAttributesTransfer;
use Spryker\Client\CartCodesRestApi\CartCodesRestApiClientInterface;
use Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder\CartCodeRestResponseBuilderInterface;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartCodeAdder implements CartCodeAdderInterface
{
    /**
     * @var CartCodesRestApiClientInterface
     */
    protected $cartCodesRestApiClient;

    /**
     * @var CartCodeRestResponseBuilderInterface
     */
    protected $cartCodeResponseBuilder;

    /**
     * @param CartCodesRestApiClientInterface $cartCodesClient
     * @param CartCodeRestResponseBuilderInterface $cartCodeResponseBuilder
     */
    public function __construct(
        CartCodesRestApiClientInterface $cartCodesClient,
        CartCodeRestResponseBuilderInterface $cartCodeResponseBuilder
    ) {
        $this->cartCodesRestApiClient = $cartCodesClient;
        $this->cartCodeResponseBuilder = $cartCodeResponseBuilder;
    }


    /**
     * @param RestRequestInterface $restRequest
     * @param RestDiscountsRequestAttributesTransfer $restDiscountRequestAttributesTransfer
     *
     * @return RestResponseInterface
     */
    public function addCandidate(RestRequestInterface $restRequest, RestDiscountsRequestAttributesTransfer $restDiscountRequestAttributesTransfer): RestResponseInterface
    {
        $cartResource = $restRequest->findParentResourceByType(CartsRestApiConfig::RESOURCE_CARTS);

        if (!$cartResource) {
           //TODO:
        }

        $cartCodeOperationResultTransfer = $this->cartCodesRestApiClient->addCandidate(
            $this->createQuoteTransfer($cartResource->getId()),
            $restDiscountRequestAttributesTransfer->getCode()
        );

        return $this->cartCodeResponseBuilder->buildCartRestResponse($cartCodeOperationResultTransfer);
    }

    /**
     * @param string $uuid
     *
     * @return QuoteTransfer
     */
    protected function createQuoteTransfer(string $uuid): QuoteTransfer
    {
        return (new QuoteTransfer())->setUuid($uuid);
    }
}
