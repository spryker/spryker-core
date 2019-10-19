<?php

namespace Spryker\CartCodesRestApi\src\Spryker\Glue\CartCodesRestApi\Processor\CartCodeAdder;

use Generated\Shared\Transfer\CustomerTransfer;
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
     * @var \Spryker\Client\CartCodesRestApi\CartCodesRestApiClientInterface
     */
    protected $cartCodesRestApiClient;

    /**
     * @var \Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder\CartCodeRestResponseBuilderInterface
     */
    protected $cartCodeResponseBuilder;

    /**
     * @param \Spryker\Client\CartCodesRestApi\CartCodesRestApiClientInterface $cartCodesClient
     * @param \Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder\CartCodeRestResponseBuilderInterface $cartCodeResponseBuilder
     */
    public function __construct(
        CartCodesRestApiClientInterface $cartCodesClient,
        CartCodeRestResponseBuilderInterface $cartCodeResponseBuilder
    ) {
        $this->cartCodesRestApiClient = $cartCodesClient;
        $this->cartCodeResponseBuilder = $cartCodeResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestDiscountsRequestAttributesTransfer $restDiscountRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addCandidate(
        RestRequestInterface $restRequest,
        RestDiscountsRequestAttributesTransfer $restDiscountRequestAttributesTransfer
    ): RestResponseInterface {
        $cartCodeOperationResultTransfer = $this->cartCodesRestApiClient->addCandidate(
            $this->createQuoteTransfer($restRequest),
            $restDiscountRequestAttributesTransfer->getCode()
        );

        return $this->cartCodeResponseBuilder->buildCartRestResponse($cartCodeOperationResultTransfer, $restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(RestRequestInterface $restRequest): QuoteTransfer
    {
        $cartResource = $restRequest->findParentResourceByType(CartsRestApiConfig::RESOURCE_CARTS);
        $customerReference = $restRequest->getRestUser()->getNaturalIdentifier();
        $customerTransfer = (new CustomerTransfer())->setCustomerReference($customerReference);

        return (new QuoteTransfer())
            ->setUuid($cartResource->getId())
            ->setCustomer($customerTransfer)
            ->setCustomerReference($customerReference);
    }
}
