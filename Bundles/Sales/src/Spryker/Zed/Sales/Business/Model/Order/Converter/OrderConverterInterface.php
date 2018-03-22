<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order\Converter;

use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

interface OrderConverterInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $spySalesOrder
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function convertToEntityTransfer(SpySalesOrder $spySalesOrder): SpySalesOrderEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function convertFromEntityTransfer(SpySalesOrderEntityTransfer $salesOrderEntityTransfer): SpySalesOrder;
}
