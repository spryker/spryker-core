<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodRestResponseBuilderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodSorterInterface;

class ShipmentMethodByCheckoutDataExpander implements ShipmentMethodByCheckoutDataExpanderInterface
{
    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodRestResponseBuilderInterface
     */
    protected $shipmentMethodRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapperInterface
     */
    protected $shipmentMethodMapper;

    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodSorterInterface
     */
    protected $shipmentMethodSorter;

    /**
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodRestResponseBuilderInterface $shipmentMethodRestResponseBuilder
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapperInterface $shipmentMethodMapper
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodSorterInterface $shipmentMethodSorter
     */
    public function __construct(
        ShipmentMethodRestResponseBuilderInterface $shipmentMethodRestResponseBuilder,
        ShipmentMethodMapperInterface $shipmentMethodMapper,
        ShipmentMethodSorterInterface $shipmentMethodSorter
    ) {
        $this->shipmentMethodRestResponseBuilder = $shipmentMethodRestResponseBuilder;
        $this->shipmentMethodMapper = $shipmentMethodMapper;
        $this->shipmentMethodSorter = $shipmentMethodSorter;
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
            $restCheckoutDataTransfer = $resource->getPayload();
            if (!$restCheckoutDataTransfer instanceof RestCheckoutDataTransfer) {
                continue;
            }

            $shipmentMethodsTransfer = $restCheckoutDataTransfer->getShipmentMethods();
            if (!$shipmentMethodsTransfer) {
                continue;
            }

            $restShipmentMethodsAttributesTransfers = $this->mapRestShipmentMethodsAttributesTransfers(
                $shipmentMethodsTransfer
            );

            $restShipmentMethodsAttributesTransfers = $this->sortRestShipmentMethodsAttributesTransfers(
                $restShipmentMethodsAttributesTransfers,
                $restRequest
            );

            $this->addShipmentMethodResourceRelationships($restShipmentMethodsAttributesTransfers, $resource);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer[]
     */
    protected function mapRestShipmentMethodsAttributesTransfers(
        ShipmentMethodsTransfer $shipmentMethodsTransfer
    ): array {
        return $this->shipmentMethodMapper
            ->mapShipmentMethodTransfersToRestShipmentMethodsAttributesTransfers(
                $shipmentMethodsTransfer->getMethods()->getArrayCopy()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer[] $restShipmentMethodsAttributesTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer[]
     */
    protected function sortRestShipmentMethodsAttributesTransfers(
        array $restShipmentMethodsAttributesTransfers,
        RestRequestInterface $restRequest
    ): array {
        return $this->shipmentMethodSorter
            ->sortRestShipmentMethodsAttributesTransfers($restShipmentMethodsAttributesTransfers, $restRequest);
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer[] $restShipmentMethodAttributesTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return void
     */
    protected function addShipmentMethodResourceRelationships(
        array $restShipmentMethodAttributesTransfers,
        RestResourceInterface $resource
    ): void {
        foreach ($restShipmentMethodAttributesTransfers as $idShipmentMethod => $restShipmentMethodAttributesTransfer) {
            $shipmentMethodRestResource = $this->shipmentMethodRestResponseBuilder->createShipmentMethodRestResource(
                (string)$idShipmentMethod,
                $restShipmentMethodAttributesTransfer
            );

            $resource->addRelationship($shipmentMethodRestResource);
        }
    }
}
