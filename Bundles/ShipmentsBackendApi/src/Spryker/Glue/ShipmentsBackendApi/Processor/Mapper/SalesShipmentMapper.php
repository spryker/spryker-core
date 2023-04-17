<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiSalesShipmentsAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\SalesShipmentCollectionTransfer;
use Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Glue\ShipmentsBackendApi\ShipmentsBackendApiConfig;

class SalesShipmentMapper implements SalesShipmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesShipmentCollectionTransfer $salesShipmentCollectionTransfer
     * @param \Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer $salesShipmentResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer
     */
    public function mapSalesShipmentCollectionToSalesShipmentResourceCollection(
        SalesShipmentCollectionTransfer $salesShipmentCollectionTransfer,
        SalesShipmentResourceCollectionTransfer $salesShipmentResourceCollectionTransfer
    ): SalesShipmentResourceCollectionTransfer {
        foreach ($salesShipmentCollectionTransfer->getShipments() as $shipmentTransfer) {
            $glueResourceTransfer = $this->mapShipmentTransferToSalesShipmentResourceTransfer(
                $shipmentTransfer,
                new GlueResourceTransfer(),
            );

            $salesShipmentResourceCollectionTransfer
                ->addShipment($shipmentTransfer)
                ->addSalesShipmentResource($glueResourceTransfer);
        }

        return $salesShipmentResourceCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $salesShipmentResourceTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function mapShipmentTransferToSalesShipmentResourceTransfer(
        ShipmentTransfer $shipmentTransfer,
        GlueResourceTransfer $salesShipmentResourceTransfer
    ): GlueResourceTransfer {
        $apiSalesShipmentsAttributesTransfer = $this->mapShipmentTransferToApiSalesShipmentAttributesTransfer(
            $shipmentTransfer,
            new ApiSalesShipmentsAttributesTransfer(),
        );

        return $salesShipmentResourceTransfer
            ->setType(ShipmentsBackendApiConfig::RESOURCE_SALES_SHIPMENTS)
            ->setId($shipmentTransfer->getUuidOrFail())
            ->setAttributes($apiSalesShipmentsAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\ApiSalesShipmentsAttributesTransfer $apiSalesShipmentsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiSalesShipmentsAttributesTransfer
     */
    protected function mapShipmentTransferToApiSalesShipmentAttributesTransfer(
        ShipmentTransfer $shipmentTransfer,
        ApiSalesShipmentsAttributesTransfer $apiSalesShipmentsAttributesTransfer
    ): ApiSalesShipmentsAttributesTransfer {
        return $apiSalesShipmentsAttributesTransfer->fromArray($shipmentTransfer->toArray(), true);
    }
}
