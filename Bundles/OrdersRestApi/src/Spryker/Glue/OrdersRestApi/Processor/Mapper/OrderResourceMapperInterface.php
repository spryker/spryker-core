<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\OrdersRestAttributesTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface OrdersResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrdersRestAttributesTransfer
     */
    public function mapOrderTransferToOrdersRestAttributesTransfer(OrderTransfer $orderTransfer): OrdersRestAttributesTransfer;
}
