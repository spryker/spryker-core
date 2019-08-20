<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapperInterface;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;

class ShipmentMethodRestResponseBuilder implements ShipmentMethodRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapperInterface
     */
    protected $shipmentMethodMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapperInterface $shipmentMethodMapper
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder, ShipmentMethodMapperInterface $shipmentMethodMapper)
    {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->shipmentMethodMapper = $shipmentMethodMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer $restShipmentMethodAttributesTransfer
     * @param string $idShipmentMethod
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createShipmentMethodRestResource(
        RestShipmentMethodAttributesTransfer $restShipmentMethodAttributesTransfer,
        string $idShipmentMethod
    ): RestResourceInterface {
        return $this->restResourceBuilder->createRestResource(
            ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS,
            $idShipmentMethod,
            $restShipmentMethodAttributesTransfer
        );
    }
}
