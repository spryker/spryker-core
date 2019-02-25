<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\StrategyResolver;

use Spryker\Zed\Sales\Business\Order\OrderHydratorInterface;

/**
 * @deprecated Will be removed in next major release.
 */
interface OrderHydratorStrategyResolverInterface
{
    public const STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT = 'STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT';
    public const STRATEGY_KEY_WITH_MULTI_SHIPMENT = 'STRATEGY_KEY_WITH_MULTI_SHIPMENT';

    /**
     * @return \Spryker\Zed\Sales\Business\Order\OrderHydratorInterface
     */
    public function resolve(): OrderHydratorInterface;

    /**
     * @param iterable|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItemEntities
     *
     * @return \Spryker\Zed\Sales\Business\Order\OrderHydratorInterface
     */
    public function resolveByOrderItemEntities(iterable $salesOrderItemEntities);
}
