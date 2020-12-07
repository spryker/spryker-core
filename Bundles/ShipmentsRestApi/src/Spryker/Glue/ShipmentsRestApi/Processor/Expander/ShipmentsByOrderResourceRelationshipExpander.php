<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Expander;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Factory\ShipmentServiceFactoryInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\OrderShipmentRestResponseBuilderInterface;

class ShipmentsByOrderResourceRelationshipExpander implements ShipmentsByOrderResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\OrderShipmentRestResponseBuilderInterface
     */
    protected $orderShipmentRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\Factory\ShipmentServiceFactoryInterface
     */
    protected $shipmentServiceFactory;

    /**
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\OrderShipmentRestResponseBuilderInterface $orderShipmentRestResponseBuilder
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Factory\ShipmentServiceFactoryInterface $shipmentServiceFactory
     */
    public function __construct(
        OrderShipmentRestResponseBuilderInterface $orderShipmentRestResponseBuilder,
        ShipmentServiceFactoryInterface $shipmentServiceFactory
    ) {
        $this->orderShipmentRestResponseBuilder = $orderShipmentRestResponseBuilder;
        $this->shipmentServiceFactory = $shipmentServiceFactory;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $orderTransfer = $resource->getPayload();
            if (!$orderTransfer instanceof OrderTransfer || !$this->isOrderUsingSplitShipments($orderTransfer)) {
                continue;
            }

            $this->addOrderShipmentsResourceRelationships($orderTransfer, $resource);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return void
     */
    protected function addOrderShipmentsResourceRelationships(
        OrderTransfer $orderTransfer,
        RestResourceInterface $resource
    ): void {
        $shipmentGroupTransfers = $this->shipmentServiceFactory
            ->getShipmentService()
            ->groupItemsByShipment($orderTransfer->getItems());

        foreach ($shipmentGroupTransfers as $shipmentGroupTransfer) {
            $orderShipmentsResource = $this->orderShipmentRestResponseBuilder
                ->createOrderShipmentRestResource($shipmentGroupTransfer);

            $resource->addRelationship($orderShipmentsResource);
        }
    }

    /**
     * @deprecated Exists for BC reasons only.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function isOrderUsingSplitShipments(OrderTransfer $orderTransfer): bool
    {
        return !$orderTransfer->getShippingAddress() && !$orderTransfer->getIdShipmentMethod();
    }
}
