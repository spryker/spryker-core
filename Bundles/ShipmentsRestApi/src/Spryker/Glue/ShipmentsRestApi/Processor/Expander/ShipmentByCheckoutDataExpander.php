<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestShipmentsAttributesTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShipmentsRestApi\Dependency\Service\ShipmentsRestApiToShipmentServiceInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMapperInterface;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;

class ShipmentByCheckoutDataExpander implements ShipmentByCheckoutDataExpanderInterface
{
    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Dependency\Service\ShipmentsRestApiToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMapperInterface
     */
    protected $shipmentMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\ShipmentsRestApi\Dependency\Service\ShipmentsRestApiToShipmentServiceInterface $shipmentService
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMapperInterface $shipmentMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        ShipmentsRestApiToShipmentServiceInterface $shipmentService,
        ShipmentMapperInterface $shipmentMapper,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->shipmentService = $shipmentService;
        $this->shipmentMapper = $shipmentMapper;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        if ($this->isSingleShipmentRequest($restRequest)) {
            return;
        }

        foreach ($resources as $resource) {
            $quoteTransfer = $this->findQuoteTransferInPayload($resource);
            $shipmentMethodsCollectionTransfer = $this->findShipmentMethodsCollectionTransferInPayload($resource);

            if (!$quoteTransfer || !$shipmentMethodsCollectionTransfer) {
                continue;
            }

            $mappedShipmentMethodsByHash = $this->mapShipmentMethodsByHash($shipmentMethodsCollectionTransfer);

            $shipmentGroupTransfers = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());
            foreach ($shipmentGroupTransfers as $shipmentGroupTransfer) {
                $this->addShipmentsResourceRelationships(
                    $resource,
                    $shipmentGroupTransfer,
                    $mappedShipmentMethodsByHash[$shipmentGroupTransfer->getHash()] ?? null
                );
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer $shipmentMethodsCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer[]
     */
    protected function mapShipmentMethodsByHash(ShipmentMethodsCollectionTransfer $shipmentMethodsCollectionTransfer): array
    {
        $mappedShipmentMethodsByHash = [];

        foreach ($shipmentMethodsCollectionTransfer->getShipmentMethods() as $shipmentMethodsTransfer) {
            $mappedShipmentMethodsByHash[$shipmentMethodsTransfer->getShipmentHash()] = $shipmentMethodsTransfer;
        }

        return $mappedShipmentMethodsByHash;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer|null $shipmentMethodsTransfer
     *
     * @return void
     */
    protected function addShipmentsResourceRelationships(
        RestResourceInterface $resource,
        ShipmentGroupTransfer $shipmentGroupTransfer,
        ?ShipmentMethodsTransfer $shipmentMethodsTransfer
    ): void {
        $shipmentGroupTransfer->setAvailableShipmentMethods($shipmentMethodsTransfer);

        $restShipmentsAttributesTransfer = $this->shipmentMapper
            ->mapShipmentGroupTransferToRestShipmentsAttributesTransfers(
                $shipmentGroupTransfer,
                new RestShipmentsAttributesTransfer()
            );

        $shipmentsRestResource = $this->restResourceBuilder->createRestResource(
            ShipmentsRestApiConfig::RESOURCE_SHIPMENTS,
            $shipmentGroupTransfer->getHash(),
            $restShipmentsAttributesTransfer
        )->setPayload($shipmentGroupTransfer);

        $resource->addRelationship($shipmentsRestResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function findQuoteTransferInPayload(RestResourceInterface $resource): ?QuoteTransfer
    {
        $restCheckoutDataTransfer = $resource->getPayload();
        if (!$restCheckoutDataTransfer instanceof RestCheckoutDataTransfer) {
            return null;
        }

        return $restCheckoutDataTransfer->getQuote();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer|null
     */
    protected function findShipmentMethodsCollectionTransferInPayload(
        RestResourceInterface $resource
    ): ?ShipmentMethodsCollectionTransfer {
        $restCheckoutDataTransfer = $resource->getPayload();
        if (!$restCheckoutDataTransfer instanceof RestCheckoutDataTransfer) {
            return null;
        }

        return $restCheckoutDataTransfer->getAvailableShipmentMethods();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isSingleShipmentRequest(RestRequestInterface $restRequest): bool
    {
        $restCheckoutRequestAttributesTransfer = $restRequest->getResource()->getAttributes();

        if (!$restCheckoutRequestAttributesTransfer instanceof RestCheckoutRequestAttributesTransfer) {
            return false;
        }

        return $restCheckoutRequestAttributesTransfer->getShippingAddress() || $restCheckoutRequestAttributesTransfer->getShipment();
    }
}
