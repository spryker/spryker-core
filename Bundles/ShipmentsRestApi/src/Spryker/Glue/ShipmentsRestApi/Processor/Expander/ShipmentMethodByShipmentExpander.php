<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Expander;

use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodRestResponseBuilderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodSorterInterface;

class ShipmentMethodByShipmentExpander implements ShipmentMethodByShipmentExpanderInterface
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
            $shipmentGroupTransfer = $resource->getPayload();
            if (!$shipmentGroupTransfer instanceof ShipmentGroupTransfer) {
                continue;
            }

            if (!$shipmentGroupTransfer->getAvailableShipmentMethods()) {
                continue;
            }

            $restShipmentMethodsAttributesTransfers = $this->shipmentMethodMapper
                ->mapShipmentMethodTransfersToRestShipmentMethodsAttributesTransfers(
                    $shipmentGroupTransfer->getAvailableShipmentMethods()->getMethods()
                );

            $restShipmentMethodsAttributesTransfers = $this->shipmentMethodSorter
                ->sortRestShipmentMethodsAttributesTransfers($restShipmentMethodsAttributesTransfers, $restRequest);

            foreach ($restShipmentMethodsAttributesTransfers as $idShipmentMethod => $restShipmentMethodsAttributesTransfer) {
                $shipmentMethodRestResource = $this->shipmentMethodRestResponseBuilder
                    ->createShipmentMethodRestResource((string)$idShipmentMethod, $restShipmentMethodsAttributesTransfer);

                $resource->addRelationship($shipmentMethodRestResource);
            }
        }
    }
}
