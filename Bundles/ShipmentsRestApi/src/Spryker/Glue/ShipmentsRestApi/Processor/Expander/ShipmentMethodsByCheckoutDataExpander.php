<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Expander;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodsMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodsRestResponseBuilderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodSorterInterface;

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
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodSorterInterface
     */
    protected $shipmentMethodSorter;

    /**
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodsRestResponseBuilderInterface $shipmentMethodRestResponseBuilder
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodsMapperInterface $shipmentMethodMapper
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodSorterInterface $shipmentMethodSorter
     */
    public function __construct(
        ShipmentMethodsRestResponseBuilderInterface $shipmentMethodRestResponseBuilder,
        ShipmentMethodsMapperInterface $shipmentMethodMapper,
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

            $currentStoreTransfer = $restCheckoutDataTransfer->getCurrentStore();
            if (!$currentStoreTransfer) {
                continue;
            }

            $restShipmentMethodAttributesTransfers = $this->getSortedRestShipmentMethodsAttributesTransfers(
                $shipmentMethodsTransfer,
                $currentStoreTransfer,
                $restRequest
            );

            $this->addShipmentMethodResourceRelationships($restShipmentMethodAttributesTransfers, $resource);
        }

        return $resources;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $currentStoreTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer[]
     */
    protected function getSortedRestShipmentMethodsAttributesTransfers(
        ShipmentMethodsTransfer $shipmentMethodsTransfer,
        StoreTransfer $currentStoreTransfer,
        RestRequestInterface $restRequest
    ): array {
        $restShipmentMethodAttributesTransfers = [];
        $shipmentMethodTransfers = $shipmentMethodsTransfer->getMethods()->getArrayCopy();

        $restShipmentMethodAttributesTransfers = $this->shipmentMethodMapper
            ->mapShipmentMethodTransfersToRestShipmentMethodsAttributesTransfers(
                $shipmentMethodTransfers,
                $restShipmentMethodAttributesTransfers
            );

        $restShipmentMethodAttributesTransfers = $this->addShipmentMethodPricesToRestShipmentMethodsAttributesTransfers(
            $restShipmentMethodAttributesTransfers,
            $shipmentMethodTransfers,
            $currentStoreTransfer
        );

        return $this->shipmentMethodSorter
            ->sortRestShipmentMethodsAttributesTransfers($restShipmentMethodAttributesTransfers, $restRequest);
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
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array
     */
    protected function addShipmentMethodPricesToRestShipmentMethodsAttributesTransfers(
        array $restShipmentMethodsAttributesTransfers,
        array $shipmentMethodTransfers,
        StoreTransfer $storeTransfer
    ): array {
        foreach ($restShipmentMethodsAttributesTransfers as $idShipmentMethod => $restShipmentMethodsAttributesTransfer) {
            foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
                $restShipmentMethodsAttributesTransfer = $this->setPricesToRestShipmentMethodsAttributesTransfer(
                   $idShipmentMethod,
                   $shipmentMethodTransfer,
                   $storeTransfer,
                   $restShipmentMethodsAttributesTransfer
               );
            }
        }

        return $restShipmentMethodsAttributesTransfers;
    }

    /**
     * @param int $idShipmentMethod
     * @param ShipmentMethodTransfer $shipmentMethodTransfer
     * @param StoreTransfer $storeTransfer
     * @param RestShipmentMethodsAttributesTransfer $restShipmentMethodsAttributesTransfer
     *
     * @return RestShipmentMethodsAttributesTransfer
     */
    protected function setPricesToRestShipmentMethodsAttributesTransfer(
        int $idShipmentMethod,
        ShipmentMethodTransfer $shipmentMethodTransfer,
        StoreTransfer $storeTransfer,
        RestShipmentMethodsAttributesTransfer $restShipmentMethodsAttributesTransfer
    ): RestShipmentMethodsAttributesTransfer {
        if ($idShipmentMethod === $shipmentMethodTransfer->getIdShipmentMethod()) {
            $moneyValueTransfer = $this->findMoneyValueTransfer($shipmentMethodTransfer, $storeTransfer);
            if ($moneyValueTransfer) {
                $restShipmentMethodsAttributesTransfer->setDefaultGrossPrice($moneyValueTransfer->getGrossAmount());
                $restShipmentMethodsAttributesTransfer->setDefaultNetPrice($moneyValueTransfer->getNetAmount());
            }
        }

        return $restShipmentMethodsAttributesTransfer;
    }

    /**
     * @param ShipmentMethodTransfer $shipmentMethodTransfer
     * @param StoreTransfer $storeTransfer
     *
     * @return MoneyValueTransfer|null
     */
    protected function findMoneyValueTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        StoreTransfer $storeTransfer
    ): ?MoneyValueTransfer {
        foreach ($shipmentMethodTransfer->getPrices() as $moneyValueTransfer) {
            if ($this->isMoneyValueTransferForCurrentStoreAndCurrency(
                $moneyValueTransfer,
                $storeTransfer,
                $shipmentMethodTransfer
            )) {
                return $moneyValueTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    protected function isMoneyValueTransferForCurrentStoreAndCurrency(
        MoneyValueTransfer $moneyValueTransfer,
        StoreTransfer $storeTransfer,
        ShipmentMethodTransfer $shipmentMethodTransfer
    ): bool {
        return $moneyValueTransfer->getFkStore() === $storeTransfer->getIdStore()
            && $moneyValueTransfer->getCurrency()->getCode() === $shipmentMethodTransfer->getCurrencyIsoCode();
    }
}
