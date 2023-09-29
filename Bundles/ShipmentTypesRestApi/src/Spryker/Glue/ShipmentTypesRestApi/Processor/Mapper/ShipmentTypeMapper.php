<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestShipmentTypesAttributesTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;

class ShipmentTypeMapper implements ShipmentTypeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer
     * @param \Generated\Shared\Transfer\RestShipmentTypesAttributesTransfer $restShipmentTypesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestShipmentTypesAttributesTransfer
     */
    public function mapShipmentTypeStorageTransferToRestShipmentTypesAttributesTransfer(
        ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer,
        RestShipmentTypesAttributesTransfer $restShipmentTypesAttributesTransfer
    ): RestShipmentTypesAttributesTransfer {
        return $restShipmentTypesAttributesTransfer->fromArray($shipmentTypeStorageTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param \Generated\Shared\Transfer\RestShipmentTypesAttributesTransfer $restShipmentTypesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestShipmentTypesAttributesTransfer
     */
    public function mapShipmentTypeTransferToRestShipmentTypesAttributesTransfer(
        ShipmentTypeTransfer $shipmentTypeTransfer,
        RestShipmentTypesAttributesTransfer $restShipmentTypesAttributesTransfer
    ): RestShipmentTypesAttributesTransfer {
        return $restShipmentTypesAttributesTransfer->fromArray($shipmentTypeTransfer->toArray(), true);
    }
}
