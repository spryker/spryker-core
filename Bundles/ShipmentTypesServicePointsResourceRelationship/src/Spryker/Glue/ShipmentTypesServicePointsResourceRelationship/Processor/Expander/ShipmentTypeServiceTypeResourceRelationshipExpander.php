<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Expander;

use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Reader\ServiceTypeReaderInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ShipmentTypeServiceTypeResourceRelationshipExpander implements ShipmentTypeServiceTypeResourceRelationshipExpanderInterface
{
    /**
     * @uses \Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES
     *
     * @var string
     */
    protected const RESOURCE_SHIPMENT_TYPES = 'shipment-types';

    /**
     * @var \Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Reader\ServiceTypeReaderInterface
     */
    protected ServiceTypeReaderInterface $serviceTypeReader;

    /**
     * @param \Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Reader\ServiceTypeReaderInterface $serviceTypeReader
     */
    public function __construct(ServiceTypeReaderInterface $serviceTypeReader)
    {
        $this->serviceTypeReader = $serviceTypeReader;
    }

    /**
     * @param list<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $shipmentTypeRestResources = $this->extractShipmentTypesResources($resources);
        if (!$shipmentTypeRestResources) {
            return;
        }

        $serviceTypeUuids = $this->extractServiceTypeUuids($shipmentTypeRestResources);
        if ($serviceTypeUuids === []) {
            return;
        }

        $serviceTypeResourcesIndexedByServiceTypeUuid = $this->serviceTypeReader->getServiceTypeRestResourcesIndexedByServiceTypeUuid(
            $serviceTypeUuids,
        );

        foreach ($shipmentTypeRestResources as $shipmentTypeRestResource) {
            /** @var \Generated\Shared\Transfer\ShipmentTypeStorageTransfer $payload */
            $payload = $shipmentTypeRestResource->getPayload();
            if (!$this->isServiceTypeDataProvided($payload)) {
                continue;
            }

            $serviceTypeUuid = $payload->getServiceTypeOrFail()->getUuidOrFail();
            if (!isset($serviceTypeResourcesIndexedByServiceTypeUuid[$serviceTypeUuid])) {
                continue;
            }

            $shipmentTypeRestResource->addRelationship(
                $serviceTypeResourcesIndexedByServiceTypeUuid[$serviceTypeUuid],
            );
        }
    }

    /**
     * @param list<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $restResources
     *
     * @return list<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    protected function extractShipmentTypesResources(array $restResources): array
    {
        $shipmentTypeRestResources = [];
        foreach ($restResources as $restResource) {
            if ($restResource->getType() === static::RESOURCE_SHIPMENT_TYPES) {
                $shipmentTypeRestResources[] = $restResource;
            }
        }

        return $shipmentTypeRestResources;
    }

    /**
     * @param list<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $shipmentTypeRestResources
     *
     * @return list<string>
     */
    protected function extractServiceTypeUuids(array $shipmentTypeRestResources): array
    {
        $serviceTypeUuids = [];
        foreach ($shipmentTypeRestResources as $shipmentTypeRestResource) {
            /** @var \Generated\Shared\Transfer\ShipmentTypeStorageTransfer $payload */
            $payload = $shipmentTypeRestResource->getPayload();
            if (!$this->isServiceTypeDataProvided($payload)) {
                continue;
            }

            $serviceTypeUuids[] = $payload->getServiceTypeOrFail()->getUuidOrFail();
        }

        return array_unique(array_filter($serviceTypeUuids));
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $shipmentTypeRestResourcePayload
     *
     * @return bool
     */
    protected function isServiceTypeDataProvided(?AbstractTransfer $shipmentTypeRestResourcePayload): bool
    {
        return $shipmentTypeRestResourcePayload instanceof ShipmentTypeStorageTransfer
            && $shipmentTypeRestResourcePayload->getServiceType() !== null
            && $shipmentTypeRestResourcePayload->getServiceTypeOrFail()->getUuid() !== null;
    }
}
