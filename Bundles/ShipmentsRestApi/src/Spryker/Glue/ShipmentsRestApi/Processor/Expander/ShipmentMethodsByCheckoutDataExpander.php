<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodsMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodsRestResponseBuilderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodsSorterInterface;

class ShipmentMethodsByCheckoutDataExpander implements ShipmentMethodsByCheckoutDataExpanderInterface
{
    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodsRestResponseBuilderInterface
     */
    protected $shipmentMethodRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodsMapperInterface
     */
    protected $shipmentMethodMapper;

    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodsSorterInterface
     */
    protected $shipmentMethodsSorter;

    /**
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodsRestResponseBuilderInterface $shipmentMethodRestResponseBuilder
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodsMapperInterface $shipmentMethodMapper
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodsSorterInterface $shipmentMethodsSorter
     */
    public function __construct(
        ShipmentMethodsRestResponseBuilderInterface $shipmentMethodRestResponseBuilder,
        ShipmentMethodsMapperInterface $shipmentMethodMapper,
        ShipmentMethodsSorterInterface $shipmentMethodsSorter
    ) {

        $this->shipmentMethodRestResponseBuilder = $shipmentMethodRestResponseBuilder;
        $this->shipmentMethodMapper = $shipmentMethodMapper;
        $this->shipmentMethodsSorter = $shipmentMethodsSorter;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $shipmentMethodsTransfer = $this->findShipmentMethodsInPayload($resource);
            if (!$shipmentMethodsTransfer) {
                continue;
            }

            $currentStoreTransfer = $this->findCurrentStoreInPayload($resource);
            if (!$currentStoreTransfer) {
                continue;
            }

            $restShipmentMethodAttributesTransfers = $this->shipmentMethodMapper
                ->mapShipmentMethodTransfersToRestShipmentMethodAttributesTransfers(
                    $shipmentMethodsTransfer->getMethods(),
                    $currentStoreTransfer
                );

            $restShipmentMethodAttributesTransfers = $this->shipmentMethodsSorter
                ->sortShipmentMethods($restShipmentMethodAttributesTransfers, $restRequest);

            foreach ($restShipmentMethodAttributesTransfers as $idShipmentMethod => $restShipmentMethodAttributesTransfer) {
                $shipmentMethodRestResource = $this->createShipmentMethodRestResourceByCheckoutDataExpander(
                    $restShipmentMethodAttributesTransfer,
                    $idShipmentMethod
                );

                $resource->addRelationship($shipmentMethodRestResource);
            }
        }

        return $resources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer|null
     */
    protected function findShipmentMethodsInPayload(RestResourceInterface $restResource): ?ShipmentMethodsTransfer
    {
        $restCheckoutDataTransfer = $this->getPayloadAsCheckoutData($restResource);
        if (!$restCheckoutDataTransfer) {
            return null;
        }

        return $restCheckoutDataTransfer->getShipmentMethods();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer|null
     */
    protected function getPayloadAsCheckoutData(RestResourceInterface $restResource): ?RestCheckoutDataTransfer
    {
        $payload = $restResource->getPayload();

        if (!$payload || !($payload instanceof RestCheckoutDataTransfer)) {
            return null;
        }

        return $payload;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    protected function findCurrentStoreInPayload(RestResourceInterface $restResource): ?StoreTransfer
    {
        $restCheckoutDataTransfer = $this->getPayloadAsCheckoutData($restResource);
        if (!$restCheckoutDataTransfer) {
            return null;
        }

        return $restCheckoutDataTransfer->getCurrentStore();
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer $restShipmentMethodAttributesTransfer
     * @param int $idShipmentMethod
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createShipmentMethodRestResourceByCheckoutDataExpander(
        RestShipmentMethodAttributesTransfer $restShipmentMethodAttributesTransfer,
        int $idShipmentMethod
    ): RestResourceInterface {
        return $this->shipmentMethodRestResponseBuilder
            ->createShipmentMethodRestResource(
                $restShipmentMethodAttributesTransfer,
                (string)$idShipmentMethod
            );
    }
}
