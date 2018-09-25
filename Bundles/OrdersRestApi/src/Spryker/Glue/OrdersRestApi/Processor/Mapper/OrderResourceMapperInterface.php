<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\OrderDetailsRestAttributesTransfer;
use Generated\Shared\Transfer\OrderRestAttributesTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface OrderResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderRestAttributesTransfer
     */
    public function mapOrderTransferToOrderRestAttributesTransfer(OrderTransfer $orderTransfer): OrderRestAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderDetailsRestAttributesTransfer
     */
    public function mapOrderTransferToOrderDetailsRestAttributesTransfer(OrderTransfer $orderTransfer): OrderDetailsRestAttributesTransfer;
}
