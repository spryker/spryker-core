<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestShipmentsAttributesTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;

interface ShipmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\RestShipmentsAttributesTransfer $restShipmentsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestShipmentsAttributesTransfer
     */
    public function mapShipmentGroupTransferToRestShipmentsAttributesTransfers(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        RestShipmentsAttributesTransfer $restShipmentsAttributesTransfer
    ): RestShipmentsAttributesTransfer;
}
