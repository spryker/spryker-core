<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Expander;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Creator\ShipmentServiceFactoryInterface;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;

class ShipmentsByOrderResourceRelationshipExpander implements ShipmentsByOrderResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\Creator\ShipmentServiceFactoryInterface
     */
    protected $shipmentServiceFactory;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Creator\ShipmentServiceFactoryInterface $shipmentServiceFactory
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ShipmentServiceFactoryInterface $shipmentServiceFactory
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
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
            $itemTransfers = $this->findItemTransfersInPayload($resource);
            if (!$itemTransfers) {
                continue;
            }
            $shipmentGroupTransfers = $this->shipmentServiceFactory
                ->getShipmentService()
                ->groupItemsByShipment($itemTransfers);

            $this->addOrderShipmentsResourceRelationships($shipmentGroupTransfers, $resource);
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return void
     */
    protected function addOrderShipmentsResourceRelationships(
        ArrayObject $shipmentGroupTransfers,
        RestResourceInterface $resource
    ): void {
        foreach ($shipmentGroupTransfers as $shipmentGroupTransfer) {
            $shipmentTransfer = $shipmentGroupTransfer->getShipment();
            $itemsTransfers = $shipmentGroupTransfer->getItems();

            $itemUuids = [];
            foreach ($itemsTransfers as $itemTransfer) {
                $itemUuids[] = $itemTransfer->getUuid();
            }

            $restOrderShipmentsAttributesTransfer = (new RestOrderShipmentsAttributesTransfer())
                ->setItemUuids($itemUuids)
                ->setShippingAddress($shipmentTransfer->getShippingAddress())
                ->setMethodName($shipmentTransfer->getMethod()->getName())
                ->setCarrierName($shipmentTransfer->getCarrier()->getName())
                ->setRequestedDeliveryDate($shipmentTransfer->getRequestedDeliveryDate() ?? null);

            $orderShipmentsResource = $this->restResourceBuilder->createRestResource(
                ShipmentsRestApiConfig::RESOURCE_ORDER_SHIPMENTS,
                (string)$shipmentTransfer->getIdSalesShipment(),
                $restOrderShipmentsAttributesTransfer
            );

            $resource->addRelationship($orderShipmentsResource);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return \ArrayObject|null
     */
    protected function findItemTransfersInPayload(RestResourceInterface $resource): ?ArrayObject
    {
        $orderTransfer = $resource->getPayload();
        if (
            !$orderTransfer || !($orderTransfer instanceof OrderTransfer) ||
            $orderTransfer->getShippingAddress()
        ) {
            return null;
        }

        $itemTransfers = $orderTransfer->getItems();
        if (!$itemTransfers->count()) {
            return null;
        }

        return $itemTransfers;
    }
}
