<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer;
use Generated\Shared\Transfer\RestOrdersAttributesTransfer;

interface OrderMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrdersAttributesTransfer
     */
    public function mapOrderTransferToRestOrdersAttributesTransfer(OrderTransfer $orderTransfer): RestOrdersAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer
     */
    public function mapOrderTransferToRestOrderDetailsAttributesTransfer(OrderTransfer $orderTransfer): RestOrderDetailsAttributesTransfer;
}
