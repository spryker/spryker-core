<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Expander;

use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface;
use Spryker\Glue\OrdersRestApi\Processor\RestResponseBuilder\OrderRestResponseBuilderInterface;

class OrderItemExpander implements OrderItemExpanderInterface
{
    protected const ORDER_ITEM_UUID = 'orderItemUuid';

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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $customerReference = $restRequest->getUser()->getNaturalIdentifier();

        if (!$customerReference) {
            return;
        }

        $orderItemFilterTransfer = (new OrderItemFilterTransfer())
            ->setSalesOrderItemUuids($this->extractOrderItemUuids($resources))
            ->addCustomerReference($customerReference);

        $itemTransfers = $this->salesClient->getOrderItems($orderItemFilterTransfer)->getItems();
        $mappedOrderItemRestResources = $this->orderRestResponseBuilder->createMappedOrderItemRestResourcesFromOrderItemTransfers($itemTransfers);

        foreach ($resources as $resource) {
            if (!$resource->getAttributes()->offsetExists(static::ORDER_ITEM_UUID)) {
                continue;
            }

            $orderItemResource = $mappedOrderItemRestResources[$resource->getAttributes()->offsetGet(static::ORDER_ITEM_UUID)] ?? null;

            if ($orderItemResource) {
                $resource->addRelationship($orderItemResource);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return string[]
     */
    protected function extractOrderItemUuids(array $resources): array
    {
        $orderItemUuids = [];

        foreach ($resources as $resource) {
            if ($resource->getAttributes()->offsetExists(static::ORDER_ITEM_UUID)) {
                $orderItemUuids[] = $resource->getAttributes()->offsetGet(static::ORDER_ITEM_UUID);
            }
        }

        return $orderItemUuids;
    }
}
