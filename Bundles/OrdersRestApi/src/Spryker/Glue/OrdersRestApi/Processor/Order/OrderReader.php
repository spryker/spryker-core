<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Order;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface;
use Spryker\Glue\OrdersRestApi\Processor\RestResponseBuilder\OrderRestResponseBuilderInterface;

class OrderReader implements OrderReaderInterface
{
    /**
     * @var \Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface
     */
    protected $salesClient;

    /**
     * @var \Spryker\Glue\OrdersRestApi\Processor\RestResponseBuilder\OrderRestResponseBuilderInterface
     */
    protected $orderRestResponseBuilder;

    /**
     * @param \Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface $salesClient
     * @param \Spryker\Glue\OrdersRestApi\Processor\RestResponseBuilder\OrderRestResponseBuilderInterface $orderRestResponseBuilder
     */
    public function __construct(
        OrdersRestApiToSalesClientInterface $salesClient,
        OrderRestResponseBuilderInterface $orderRestResponseBuilder
    ) {
        $this->salesClient = $salesClient;
        $this->orderRestResponseBuilder = $orderRestResponseBuilder;
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
                $restRequest->getRestUser()->getNaturalIdentifier()
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
        $orderTransfer = $this->findCustomerOrderTransfer($orderReference, $customerReference);

        if ($orderTransfer->getIdSalesOrder() === null) {
            return null;
        }

        return $this->orderRestResponseBuilder->createOrderRestResource($orderTransfer);
    }

    /**
     * @param string $orderReference
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    protected function findCustomerOrderTransfer(string $orderReference, string $customerReference): ?OrderTransfer
    {
        $orderTransfer = (new OrderTransfer())
            ->setOrderReference($orderReference)
            ->setCustomerReference($customerReference);
        $orderTransfer = $this->salesClient->getCustomerOrderByOrderReference($orderTransfer);

        if ($orderTransfer->getIdSalesOrder() === null) {
            return null;
        }

        return $orderTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getOrderListAttributes(RestRequestInterface $restRequest): RestResponseInterface
    {
        $customerReference = $restRequest->getRestUser()->getNaturalIdentifier();
        $orderListRequestTransfer = (new OrderListRequestTransfer())->setCustomerReference($customerReference);

        $limit = 0;
        if ($restRequest->getPage()) {
            $limit = $restRequest->getPage()->getLimit();
            $orderListRequestTransfer->setFilter($this->createFilterTransfer($restRequest));
        }

        $orderListTransfer = $this->salesClient->getOffsetPaginatedCustomerOrderList($orderListRequestTransfer);

        $totalItems = $orderListTransfer->getPagination() ? $orderListTransfer->getPagination()->getNbResults() : 0;

        return $this->orderRestResponseBuilder->createOrderListRestResponse(
            $orderListTransfer->getOrders(),
            $totalItems,
            $limit
        );
    }

    /**
     * @param string $orderReference
     * @param string $customerReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getOrderDetailsResourceAttributes(string $orderReference, string $customerReference): RestResponseInterface
    {
        $orderTransfer = $this->findCustomerOrderTransfer($orderReference, $customerReference);

        if (!$orderTransfer) {
            return $this->orderRestResponseBuilder->createOrderNotFoundErrorResponse();
        }

        return $this->orderRestResponseBuilder->createOrderRestResponse($orderTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(RestRequestInterface $restRequest): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOffset($restRequest->getPage()->getOffset())
            ->setLimit($restRequest->getPage()->getLimit());
    }
}
