<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestShipmentsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShipmentsRestApi\Dependency\Service\ShipmentsRestApiToShipmentServiceInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentRestResponseBuilderInterface;

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
     * @var \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentRestResponseBuilderInterface
     */
    protected $shipmentRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ShipmentsRestApi\Dependency\Service\ShipmentsRestApiToShipmentServiceInterface $shipmentService
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMapperInterface $shipmentMapper
     * @param \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentRestResponseBuilderInterface $shipmentRestResponseBuilder
     */
    public function __construct(
        ShipmentsRestApiToShipmentServiceInterface $shipmentService,
        ShipmentMapperInterface $shipmentMapper,
        ShipmentRestResponseBuilderInterface $shipmentRestResponseBuilder
    ) {
        $this->shipmentService = $shipmentService;
        $this->shipmentMapper = $shipmentMapper;
        $this->shipmentRestResponseBuilder = $shipmentRestResponseBuilder;
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
            $quoteTransfer = $this->findQuoteTransferInPayload($resource);
            if (!$quoteTransfer) {
                continue;
            }

            $shipmentGroupTransfers = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());
            foreach ($shipmentGroupTransfers as $shipmentGroupTransfer) {
                $restShipmentsAttributesTransfers = $this->shipmentMapper
                    ->mapShipmentGroupTransferToRestShipmentsAttributesTransfers(
                        $shipmentGroupTransfer,
                        new RestShipmentsAttributesTransfer()
                    );

                $shipmentsRestResource = $this->shipmentRestResponseBuilder
                    ->createShipmentRestResource($shipmentGroupTransfer, $restShipmentsAttributesTransfers);

                $resource->addRelationship($shipmentsRestResource);
            }
        }
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
}
