<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OrderListTransfer;
use Propel\Runtime\Collection\ArrayCollection;

interface SalesMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ArrayCollection $orderData
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function mapSalesOrderListTransfer(ArrayCollection $orderData): OrderListTransfer;
}
