<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\SalesShipmentCollectionTransfer;
use Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer;
use Generated\Shared\Transfer\SalesShipmentsBackendApiAttributesTransfer;
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
        $salesShipmentsBackendApiAttributesTransfer = $this->mapShipmentTransferToApiSalesShipmentAttributesTransfer(
            $shipmentTransfer,
            new SalesShipmentsBackendApiAttributesTransfer(),
        );

        return $salesShipmentResourceTransfer
            ->setType(ShipmentsBackendApiConfig::RESOURCE_SALES_SHIPMENTS)
            ->setId($shipmentTransfer->getUuidOrFail())
            ->setAttributes($salesShipmentsBackendApiAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\SalesShipmentsBackendApiAttributesTransfer $salesShipmentsBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\SalesShipmentsBackendApiAttributesTransfer
     */
    protected function mapShipmentTransferToApiSalesShipmentAttributesTransfer(
        ShipmentTransfer $shipmentTransfer,
        SalesShipmentsBackendApiAttributesTransfer $salesShipmentsBackendApiAttributesTransfer
    ): SalesShipmentsBackendApiAttributesTransfer {
        return $salesShipmentsBackendApiAttributesTransfer->fromArray($shipmentTransfer->toArray(), true);
    }
}
