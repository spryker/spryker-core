<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Expander;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderShipmentsMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\OrderShipmentsRestResponseBuilderInterface;

class ShipmentsByOrderExpander implements ShipmentsByOrderExpanderInterface
{
    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\OrderShipmentsRestResponseBuilderInterface
     */
    protected $orderShipmentsRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderShipmentsMapperInterface
     */
    protected $orderShipmentsMapper;

    /**
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\OrderShipmentsRestResponseBuilderInterface $orderShipmentsRestResponseBuilder
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderShipmentsMapperInterface $orderShipmentsMapper
     */
    public function __construct(
        OrderShipmentsRestResponseBuilderInterface $orderShipmentsRestResponseBuilder,
        OrderShipmentsMapperInterface $orderShipmentsMapper
    ) {
        $this->orderShipmentsRestResponseBuilder = $orderShipmentsRestResponseBuilder;
        $this->orderShipmentsMapper = $orderShipmentsMapper;
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
            if (!$orderTransfer instanceof OrderTransfer) {
                continue;
            }

            $itemTransfers = $orderTransfer->getItems();
            if (empty($itemTransfers)) {
                continue;
            }

            $restOrderShipmentsAttributesTransfers = $this->mapItemTransfersToRestOrderShipmentsAttributesTransfer(
                $itemTransfers
            );
            $this->addOrderShipmentsResourceRelationships($restOrderShipmentsAttributesTransfers, $resource);
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer[]
     */
    protected function mapItemTransfersToRestOrderShipmentsAttributesTransfer(
        ArrayObject $itemTransfers
    ): array {
        return $this->orderShipmentsMapper
            ->mapItemTransfersToRestOrderShipmentsAttributesTransfer(
                $itemTransfers
            );
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
            $orderShipmentsResource = $this->orderShipmentsRestResponseBuilder->createOrderShipmentsRestResource(
                (string)$idOrderShipment,
                $restOrderShipmentsAttributesTransfer
            );

            $resource->addRelationship($orderShipmentsResource);
        }
    }
}
