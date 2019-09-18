<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
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
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): array
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

        return $resources;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer[]
     */
    protected function mapRestShipmentMethodsAttributesTransfers(
        ShipmentMethodsTransfer $shipmentMethodsTransfer
    ): array {
        $shipmentMethodTransfers = $shipmentMethodsTransfer->getMethods()->getArrayCopy();

        $restShipmentMethodsAttributesTransfers = $this->shipmentMethodMapper
            ->mapShipmentMethodTransfersToRestShipmentMethodsAttributesTransfers(
                $shipmentMethodTransfers
            );

        $restShipmentMethodAttributesTransfers = $this->addShipmentMethodPricesToRestShipmentMethodsAttributesTransfers(
            $restShipmentMethodsAttributesTransfers,
            $shipmentMethodTransfers
        );

        return $restShipmentMethodAttributesTransfers;
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

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodTransfer[] $restShipmentMethodsAttributesTransfers
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethodTransfers
     *
     * @return array
     */
    protected function addShipmentMethodPricesToRestShipmentMethodsAttributesTransfers(
        array $restShipmentMethodsAttributesTransfers,
        array $shipmentMethodTransfers
    ): array {
        foreach ($restShipmentMethodsAttributesTransfers as $idShipmentMethod => $restShipmentMethodsAttributesTransfer) {
            foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
                $restShipmentMethodsAttributesTransfer = $this->setPricesToRestShipmentMethodsAttributesTransfer(
                    $idShipmentMethod,
                    $shipmentMethodTransfer,
                    $restShipmentMethodsAttributesTransfer
                );
            }
        }

        return $restShipmentMethodsAttributesTransfers;
    }

    /**
     * @param int $idShipmentMethod
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer $restShipmentMethodsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer
     */
    protected function setPricesToRestShipmentMethodsAttributesTransfer(
        int $idShipmentMethod,
        ShipmentMethodTransfer $shipmentMethodTransfer,
        RestShipmentMethodsAttributesTransfer $restShipmentMethodsAttributesTransfer
    ): RestShipmentMethodsAttributesTransfer {
        if ($idShipmentMethod === $shipmentMethodTransfer->getIdShipmentMethod()) {
            $moneyValueTransfer = current($shipmentMethodTransfer->getPrices());
            if (!$moneyValueTransfer) {
                return $restShipmentMethodsAttributesTransfer;
            }

            $restShipmentMethodsAttributesTransfer->setDefaultGrossPrice($moneyValueTransfer->getGrossAmount());
            $restShipmentMethodsAttributesTransfer->setDefaultNetPrice($moneyValueTransfer->getNetAmount());
        }

        return $restShipmentMethodsAttributesTransfer;
    }
}
