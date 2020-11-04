<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Expander;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Creator\ShipmentServiceFactoryInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderShipmentsMapperInterface;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;

class ShipmentsByOrderResourceRelationshipExpander implements ShipmentsByOrderResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderShipmentsMapperInterface
     */
    protected $orderShipmentsMapper;

    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\Creator\ShipmentServiceFactoryInterface
     */
    protected $shipmentServiceFactory;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderShipmentsMapperInterface $orderShipmentsMapper
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Creator\ShipmentServiceFactoryInterface $shipmentServiceFactory
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        OrderShipmentsMapperInterface $orderShipmentsMapper,
        ShipmentServiceFactoryInterface $shipmentServiceFactory
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->orderShipmentsMapper = $orderShipmentsMapper;
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
            if (
                !$orderTransfer || !($orderTransfer instanceof OrderTransfer) ||
                $orderTransfer->getShippingAddress()
            ) {
                continue;
            }

            $itemTransfers = $orderTransfer->getItems();
            if (!$itemTransfers->count()) {
                continue;
            }

            $shipmentGroupTransfers = $this->shipmentServiceFactory
                ->getShipmentService()
                ->groupItemsByShipment($itemTransfers);

            $restOrderShipmentsAttributesTransfers = $this->orderShipmentsMapper
                ->mapShipmentGroupsTransfersToRestOrderShipmentsAttributesTransfer(
                    $shipmentGroupTransfers
                );
            $this->addOrderShipmentsResourceRelationships($restOrderShipmentsAttributesTransfers, $resource);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer[] $restOrderShipmentsAttributesTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return void
     */
    protected function addOrderShipmentsResourceRelationships(
        array $restOrderShipmentsAttributesTransfers,
        RestResourceInterface $resource
    ): void {
        foreach ($restOrderShipmentsAttributesTransfers as $idOrderShipment => $restOrderShipmentsAttributesTransfer) {
            $orderShipmentsResource = $this->restResourceBuilder->createRestResource(
                ShipmentsRestApiConfig::RESOURCE_ORDER_SHIPMENTS,
                $idOrderShipment,
                $restOrderShipmentsAttributesTransfer
            );

            $resource->addRelationship($orderShipmentsResource);
        }
    }
}
