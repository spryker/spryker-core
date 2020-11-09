<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderMapperInterface;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;

class OrderShipmentRestResponseBuilder implements OrderShipmentRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderMapperInterface
     */
    protected $orderShipmentMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderMapperInterface $orderMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        OrderMapperInterface $orderMapper,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->orderShipmentMapper = $orderMapper;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createOrderShipmentRestResource(ShipmentGroupTransfer $shipmentGroupTransfer): RestResourceInterface
    {
        $restOrderShipmentsAttributesTransfer = $this->orderShipmentMapper
            ->mapShipmentGroupTransferToRestOrderShipmentsAttributesTransfer($shipmentGroupTransfer, new RestOrderShipmentsAttributesTransfer());

        return $this->restResourceBuilder->createRestResource(
            ShipmentsRestApiConfig::RESOURCE_ORDER_SHIPMENTS,
            (string)$shipmentGroupTransfer->getShipment()->getIdSalesShipment(),
            $restOrderShipmentsAttributesTransfer
        );
    }
}
