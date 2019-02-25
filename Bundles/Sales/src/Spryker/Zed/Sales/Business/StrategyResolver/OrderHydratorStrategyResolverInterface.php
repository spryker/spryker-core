<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\StrategyResolver;

use Spryker\Zed\Sales\Business\Order\OrderHydratorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
interface OrderHydratorStrategyResolverInterface
{
    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Spryker\Zed\Sales\Business\Order\OrderHydratorInterface
     */
    public function resolve(iterable $itemTransfers): OrderHydratorInterface;

    /**
     * @param iterable|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItemEntities
     *
     * @return \Spryker\Zed\Sales\Business\Order\OrderHydratorInterface
     */
    public function resolveByOrderItemEntities(iterable $salesOrderItemEntities): OrderHydratorInterface;
}
