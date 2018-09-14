<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Orders;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToProductBundleClientInterface;
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
     * @var \Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToProductBundleClientInterface
     */
    protected $productBundleClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\OrdersRestApi\Processor\Mapper\OrdersResourceMapperInterface
     */
    protected $ordersResourceMapper;

    /**
     * @param \Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface $salesClient
     * @param \Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToProductBundleClientInterface $productBundleClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\OrdersRestApi\Processor\Mapper\OrdersResourceMapperInterface $ordersResourceMapper
     */
    public function __construct(
        OrdersRestApiToSalesClientInterface $salesClient,
        OrdersRestApiToProductBundleClientInterface $productBundleClient,
        RestResourceBuilderInterface $restResourceBuilder,
        OrdersResourceMapperInterface $ordersResourceMapper
    ) {
        $this->salesClient = $salesClient;
        $this->productBundleClient = $productBundleClient;
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
        $customerId = $restRequest->getUser()->getSurrogateIdentifier();
        $orderListTransfer = (new OrderListTransfer())->setIdCustomer((int)$customerId);

        $limit = 0;
        if ($restRequest->getPage()) {
            $offset = $restRequest->getPage()->getOffset();
            $limit = $restRequest->getPage()->getLimit();

            $orderListTransfer->setPagination($this->createPaginationTransfer(++$offset, $limit));
        }

        $orderListTransfer = $this->salesClient->getPaginatedOrder($orderListTransfer);
        $response = $this
            ->restResourceBuilder
            ->createRestResponse(
                $orderListTransfer->getPagination() !== null ? $orderListTransfer->getPagination()->getNbResults() : 0,
                $limit
            );

        foreach ($orderListTransfer->getOrders() as $orderTransfer) {
            $ordersRestAttributesTransfer = $this->ordersResourceMapper->mapOrderToOrdersRestAttributes(
                $orderTransfer,
                $this->getTransformedBundleItems($orderTransfer)
            );
            $restResource = $this->restResourceBuilder->createRestResource(
                OrdersRestApiConfig::RESOURCE_ORDERS,
                $orderTransfer->getOrderReference(),
                $ordersRestAttributesTransfer
            );

            $response->addResource($restResource);
        }

        return $response;
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
        $orderTransfer = $this->salesClient->getCustomerOrderByOrderReference($orderTransfer);

        if (!$orderTransfer->getItems()->count()) {
            return $this->createOrderNotFoundErrorResponse($response);
        }

        $ordersRestAttributesTransfer = $this->ordersResourceMapper->mapOrderToOrdersRestAttributes(
            $orderTransfer,
            $this->getTransformedBundleItems($orderTransfer)
        );
        $restResource = $this->restResourceBuilder->createRestResource(
            OrdersRestApiConfig::RESOURCE_ORDERS,
            $orderReference,
            $ordersRestAttributesTransfer
        );

        return $response->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createOrderNotFoundErrorResponse(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(OrdersRestApiConfig::RESPONSE_CODE_CANT_FIND_ORDER)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(OrdersRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ORDER);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function createPaginationTransfer(int $offset, int $limit): PaginationTransfer
    {
        return (new PaginationTransfer())
            ->setPage($offset)
            ->setMaxPerPage($limit);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderItemsTransfer[]
     */
    protected function getTransformedBundleItems(OrderTransfer $orderTransfer): array
    {
        $items = $this->productBundleClient->getGroupedBundleItems(
            $orderTransfer->getItems(),
            $orderTransfer->getBundleItems()
        );

        return $this->ordersResourceMapper->mapTransformedBundleItems($orderTransfer, $items);
    }
}
