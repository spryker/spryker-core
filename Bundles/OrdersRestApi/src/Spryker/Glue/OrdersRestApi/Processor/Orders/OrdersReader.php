<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Orders;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface;
use Spryker\Glue\OrdersRestApi\OrdersRestApiConfig;
use Spryker\Glue\OrdersRestApi\Processor\Mapper\OrdersResourceMapperInterface;
use Symfony\Component\HttpFoundation\Response;

class OrdersReader implements OrdersReaderInterface
{
    /**
     * @var \Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface
     */
    protected $salesClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\OrdersRestApi\Processor\Mapper\OrdersResourceMapperInterface $ordersResourceMapper
     */
    protected $ordersResourceMapper;

    /**
     * @param \Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface $salesClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\OrdersRestApi\Processor\Mapper\OrdersResourceMapperInterface $ordersResourceMapper
     */
    public function __construct(
        OrdersRestApiToSalesClientInterface $salesClient,
        RestResourceBuilderInterface $restResourceBuilder,
        OrdersResourceMapperInterface $ordersResourceMapper
    ) {
        $this->salesClient = $salesClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->ordersResourceMapper = $ordersResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getOrdersAttributes(RestRequestInterface $restRequest): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();

        $customerId = $restRequest->getUser()->getSurrogateIdentifier();
        $orderListData = (new OrderListTransfer())->setIdCustomer($customerId);
        $orderListData = $this->salesClient->getCustomerOrders($orderListData);

        $ordersRestAttributes = $this->ordersResourceMapper->mapOrderListToOrdersRestAttribute($orderListData);

        $restResource = $this->restResourceBuilder->createRestResource(
            OrdersRestApiConfig::RESOURCE_ORDERS,
            $restRequest->getUser()->getNaturalIdentifier(),
            $ordersRestAttributes
        );

        return $response->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getOrdersDetailsResourceAttributes(RestRequestInterface $restRequest): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();

        $orderReference = $restRequest->getResource()->getId();
        $customerReference = $restRequest->getUser()->getNaturalIdentifier();

        $orderTransfer = (new OrderTransfer())
            ->setOrderReference($orderReference)
            ->setCustomerReference($customerReference);

        $orderData = $this->salesClient->findCustomerOrderByOrderReference($orderTransfer);

        if (!$orderData->getItems()->count()) {
            return $this->createErrorResponse($response);
        }

        $ordersRestAttributes = $this->ordersResourceMapper->mapOrderToOrdersRestAttribute($orderData);

        $restResource = $this->restResourceBuilder->createRestResource(
            OrdersRestApiConfig::RESOURCE_ORDERS,
            $orderReference,
            $ordersRestAttributes
        );

        return $response->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createErrorResponse(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(OrdersRestApiConfig::RESPONSE_CODE_CANT_FIND_ORDER)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(OrdersRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ORDER);

        return $restResponse->addError($restErrorTransfer);
    }
}
