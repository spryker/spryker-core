<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Order;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface;
use Spryker\Glue\OrdersRestApi\OrdersRestApiConfig;
use Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderResourceMapperInterface;
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
            return $this->getOrderDetailsResourceAttributes(
                $restRequest->getResource()->getId(),
                $restRequest->getUser()->getNaturalIdentifier()
            );
        }

        return $this->getOrderListAttributes($restRequest);
    }

    /**
     * @param string $orderReference
     * @param string $customerReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findCustomerOrder(string $orderReference, string $customerReference): ?RestResourceInterface
    {
        $orderTransfer = (new OrderTransfer())
            ->setOrderReference($orderReference)
            ->setCustomerReference($customerReference);
        $orderTransfer = $this->salesClient->getCustomerOrderByOrderReference($orderTransfer);

        if ($orderTransfer->getIdSalesOrder() === null) {
            return null;
        }

        $restOrderDetailsAttributesTransfer = $this->orderResourceMapper->mapOrderTransferToRestOrderDetailsAttributesTransfer($orderTransfer);

        $restResource = $this->restResourceBuilder->createRestResource(
            OrdersRestApiConfig::RESOURCE_ORDERS,
            $orderReference,
            $restOrderDetailsAttributesTransfer
        );

        return $restResource;
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

            $orderListTransfer->setFilter($this->createFilterTransfer(++$offset, $limit));
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

            $response = $response->addResource(
                $this->restResourceBuilder->createRestResource(
                    OrdersRestApiConfig::RESOURCE_ORDERS,
                    $orderTransfer->getOrderReference(),
                    $restOrdersAttributesTransfer
                )
            );
        }

        return $response;
    }

    /**
     * @param string $orderReference
     * @param string $customerReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getOrderDetailsResourceAttributes(string $orderReference, string $customerReference): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();

        $orderRestResource = $this->findCustomerOrder($orderReference, $customerReference);

        if (!$orderRestResource) {
            return $this->createOrderNotFoundErrorResponse($response);
        }

        return $response->addResource($orderRestResource);
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
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(int $offset, int $limit): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOffset($offset)
            ->setLimit($limit);
    }
}
