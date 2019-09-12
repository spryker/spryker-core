<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Expander;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
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

            $restShipmentMethodAttributesTransfers = $this->getSortedShipmentMethodAttributesTransfers(
                $shipmentMethodsTransfer,
                $currentStoreTransfer,
                $restRequest
            );

            $this->addShipmentMethodResourceRelationships($restShipmentMethodAttributesTransfers, $resource);
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
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $currentStoreTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer[]
     */
    protected function getSortedShipmentMethodAttributesTransfers(
        ShipmentMethodsTransfer $shipmentMethodsTransfer,
        StoreTransfer $currentStoreTransfer,
        RestRequestInterface $restRequest
    ): array {
        $restShipmentMethodAttributesTransfers = [];
        $shipmentMethodTransfers = $shipmentMethodsTransfer->getMethods()->getArrayCopy();

        $restShipmentMethodAttributesTransfers = $this->shipmentMethodMapper
            ->mapShipmentMethodTransfersToRestShipmentMethodAttributesTransfers(
                $shipmentMethodTransfers,
                $restShipmentMethodAttributesTransfers
            );

        $restShipmentMethodAttributesTransfers = $this->addShipmentMethodPricesToRestShipmentMethodAttributesTransfers(
            $restShipmentMethodAttributesTransfers,
            $shipmentMethodTransfers,
            $currentStoreTransfer
        );

        return $this->shipmentMethodsSorter
            ->sortShipmentMethodAttributesTransfers($restShipmentMethodAttributesTransfers, $restRequest);
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer[] $restShipmentMethodAttributesTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return void
     */
    protected function addShipmentMethodResourceRelationships(
        array $restShipmentMethodAttributesTransfers,
        RestResourceInterface $resource
    ): void {
        foreach ($restShipmentMethodAttributesTransfers as $idShipmentMethod => $restShipmentMethodAttributesTransfer) {
            $shipmentMethodRestResource = $this->createShipmentMethodRestResourceByCheckoutDataExpander(
                $restShipmentMethodAttributesTransfer,
                (string)$idShipmentMethod
            );

            $resource->addRelationship($shipmentMethodRestResource);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodTransfer[] $restShipmentMethodAttributesTransfers
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethodTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array
     */
    protected function addShipmentMethodPricesToRestShipmentMethodAttributesTransfers(
        array $restShipmentMethodAttributesTransfers,
        array $shipmentMethodTransfers,
        StoreTransfer $storeTransfer
    ): array {
        foreach ($restShipmentMethodAttributesTransfers as $restShipmentMethodAttributesTransfer) {
            foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
                $defaultGrossPrice = $this->findDefaultGrossPrice($shipmentMethodTransfer, $storeTransfer);
                $defaultNetPrice = $this->findDefaultNetPrice($shipmentMethodTransfer, $storeTransfer);

                $restShipmentMethodAttributesTransfer->setDefaultGrossPrice($defaultGrossPrice);
                $restShipmentMethodAttributesTransfer->setDefaultNetPrice($defaultNetPrice);
            }
        }

        return $restShipmentMethodAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer $restShipmentMethodAttributesTransfer
     * @param string $idShipmentMethod
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createShipmentMethodRestResourceByCheckoutDataExpander(
        RestShipmentMethodAttributesTransfer $restShipmentMethodAttributesTransfer,
        string $idShipmentMethod
    ): RestResourceInterface {
        return $this->shipmentMethodRestResponseBuilder
            ->createShipmentMethodRestResource(
                $restShipmentMethodAttributesTransfer,
                $idShipmentMethod
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int|null
     */
    protected function findDefaultGrossPrice(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        StoreTransfer $storeTransfer
    ): ?int {
        foreach ($shipmentMethodTransfer->getPrices() as $priceTransfer) {
            if ($this->checkPriceTransferByCurrencyIsoCodeAndStoreId(
                $priceTransfer,
                $storeTransfer,
                $shipmentMethodTransfer
            )) {
                return $priceTransfer->getGrossAmount();
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $priceTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    protected function checkPriceTransferByCurrencyIsoCodeAndStoreId(
        MoneyValueTransfer $priceTransfer,
        StoreTransfer $storeTransfer,
        ShipmentMethodTransfer $shipmentMethodTransfer
    ): bool {
        return $priceTransfer->getFkStore() === $storeTransfer->getIdStore()
            && $priceTransfer->getCurrency()->getCode() === $shipmentMethodTransfer->getCurrencyIsoCode();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int|null
     */
    protected function findDefaultNetPrice(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        StoreTransfer $storeTransfer
    ): ?int {
        foreach ($shipmentMethodTransfer->getPrices() as $priceTransfer) {
            if ($this->checkPriceTransferByCurrencyIsoCodeAndStoreId(
                $priceTransfer,
                $storeTransfer,
                $shipmentMethodTransfer
            )) {
                return $priceTransfer->getNetAmount();
            }
        }

        return null;
    }
}
