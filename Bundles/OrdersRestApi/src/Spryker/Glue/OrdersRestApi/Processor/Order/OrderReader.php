<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Order;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface;
use Spryker\Glue\OrdersRestApi\OrdersRestApiConfig;
use Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderResourceMapperInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Symfony\Component\HttpFoundation\Response;

class OrderReader implements OrderReaderInterface
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
     * @var \Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderResourceMapperInterface
     */
    protected $orderResourceMapper;

    /**
     * @param \Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface $salesClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderResourceMapperInterface $orderResourceMapper
     */
    public function __construct(
        OrdersRestApiToSalesClientInterface $salesClient,
        RestResourceBuilderInterface $restResourceBuilder,
        OrderResourceMapperInterface $orderResourceMapper
    ) {
        $this->salesClient = $salesClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->orderResourceMapper = $orderResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getOrderAttributes(RestRequestInterface $restRequest): RestResponseInterface
    {
        if ($restRequest->getResource()->getId()) {
            return $this->getOrderDetailsResourceAttributes($restRequest);
        }

        return $this->getOrderListAttributes($restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getOrderListAttributes(RestRequestInterface $restRequest): RestResponseInterface
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
            $restOrdersAttributesTransfer = $this->orderResourceMapper->mapOrderTransferToRestOrdersAttributesTransfer($orderTransfer);
            $response = $this->createRestResource($response, $orderTransfer->getOrderReference(), $restOrdersAttributesTransfer);
        }

        return $response;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getOrderDetailsResourceAttributes(RestRequestInterface $restRequest): RestResponseInterface
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

        $restOrderDetailsAttributesTransfer = $this->orderResourceMapper->mapOrderTransferToRestOrderDetailsAttributesTransfer($orderTransfer);

        return $this->createRestResource($response, $orderTransfer->getOrderReference(), $restOrderDetailsAttributesTransfer);
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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     * @param string $orderReference
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $restOrdersAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createRestResource(RestResponseInterface $response, string $orderReference, AbstractTransfer $restOrdersAttributesTransfer): RestResponseInterface
    {
        $restResource = $this->restResourceBuilder->createRestResource(
            OrdersRestApiConfig::RESOURCE_ORDERS,
            $orderReference,
            $restOrdersAttributesTransfer
        );

        return $response->addResource($restResource);
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
}
