<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;

interface OrderShipmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \ArrayObject $restOrderShipmentTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\RestOrderShipmentTransfer[]
     */
    public function mapOrderTransferToRestOrderShipmentTransfers(
        OrderTransfer $orderTransfer,
        ArrayObject $restOrderShipmentTransfers
    ): ArrayObject;
}
