<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer;
use Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;

interface OrderMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer
     */
    public function mapOrderTransferToRestOrderDetailsAttributesTransfer(
        OrderTransfer $orderTransfer,
        RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
    ): RestOrderDetailsAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer $restOrderShipmentsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderShipmentsAttributesTransfer
     */
    public function mapShipmentGroupTransferToRestOrderShipmentsAttributesTransfer(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        RestOrderShipmentsAttributesTransfer $restOrderShipmentsAttributesTransfer
    ): RestOrderShipmentsAttributesTransfer;
}
