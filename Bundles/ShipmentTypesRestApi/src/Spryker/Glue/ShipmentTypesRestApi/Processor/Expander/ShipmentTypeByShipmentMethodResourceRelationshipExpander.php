<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestShipmentTypesAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\ShipmentTypesRestApi\Processor\Mapper\ShipmentTypeMapperInterface;
use Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiConfig;

class ShipmentTypeByShipmentMethodResourceRelationshipExpander implements ShipmentTypeByShipmentMethodResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected RestResourceBuilderInterface $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ShipmentTypesRestApi\Processor\Mapper\ShipmentTypeMapperInterface
     */
    protected ShipmentTypeMapperInterface $shipmentTypeMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ShipmentTypesRestApi\Processor\Mapper\ShipmentTypeMapperInterface $shipmentTypeMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ShipmentTypeMapperInterface $shipmentTypeMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->shipmentTypeMapper = $shipmentTypeMapper;
    }

    /**
     * @param list<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $restResources
     *
     * @return void
     */
    public function addShipmentTypesResourceRelationships(array $restResources): void
    {
        foreach ($restResources as $restResource) {
            $shipmentMethodTransfer = $restResource->getPayload();

            if (
                !$shipmentMethodTransfer instanceof ShipmentMethodTransfer
                || !$shipmentMethodTransfer->getShipmentType()
            ) {
                continue;
            }

            $restResource->addRelationship(
                $this->createShipmentTypesRestResource($shipmentMethodTransfer->getShipmentTypeOrFail()),
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createShipmentTypesRestResource(ShipmentTypeTransfer $shipmentTypeTransfer): RestResourceInterface
    {
        $restShipmentTypesAttributesTransfer = $this
            ->shipmentTypeMapper
            ->mapShipmentTypeTransferToRestShipmentTypesAttributesTransfer(
                $shipmentTypeTransfer,
                new RestShipmentTypesAttributesTransfer(),
            );

        return $this->restResourceBuilder->createRestResource(
            ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES,
            $shipmentTypeTransfer->getUuidOrFail(),
            $restShipmentTypesAttributesTransfer,
        );
    }
}
