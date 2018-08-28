<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Orders;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface;
use Spryker\Glue\OrdersRestApi\OrdersRestApiConfig;
use Spryker\Glue\OrdersRestApi\Processor\Mapper\OrdersResourceMapperInterface;

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
        $customerReference = $restRequest->getResource()->getId();
        $orderTransfer = (new OrderTransfer())->setCustomerReference($customerReference);
        $orderListTransfer = $this->salesClient->getOrderListByCustomerReference($orderTransfer);

        $ordersRestAttributes = $this->ordersResourceMapper->mapStoreToOrdersRestAttribute($orderListTransfer);

        $restResource = $this->restResourceBuilder->createRestResource(
            OrdersRestApiConfig::RESOURCE_ORDERS,
            $restRequest->getResource()->getId(),
            $ordersRestAttributes
        );

        $response = $this->restResourceBuilder->createRestResponse();

        return $response->addResource($restResource);
    }
}
