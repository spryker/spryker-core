<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodsMapperInterface;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;

class ShipmentMethodsRestResponseBuilder implements ShipmentMethodsRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodsMapperInterface
     */
    protected $shipmentMethodsMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodsMapperInterface $shipmentMethodsMapper
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder, ShipmentMethodsMapperInterface $shipmentMethodsMapper)
    {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->shipmentMethodsMapper = $shipmentMethodsMapper;
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
