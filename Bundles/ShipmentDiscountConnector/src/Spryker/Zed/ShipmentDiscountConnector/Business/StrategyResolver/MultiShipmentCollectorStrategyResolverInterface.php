<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\StrategyResolver;

use Spryker\Zed\ShipmentDiscountConnector\Business\Collector\ShipmentDiscountCollectorInterface;

/**
 * @deprecated Will be removed in next major version after multiple shipment release.
 */
interface MultiShipmentCollectorStrategyResolverInterface
{
    public const STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT = 'STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT';
    public const STRATEGY_KEY_WITH_MULTI_SHIPMENT = 'STRATEGY_KEY_WITH_MULTI_SHIPMENT';

    public const DISCOUNT_TYPE_CARRIER = 'DISCOUNT_TYPE_CARRIER';
    public const DISCOUNT_TYPE_METHOD = 'DISCOUNT_TYPE_METHOD';
    public const DISCOUNT_TYPE_PRICE = 'DISCOUNT_TYPE_PRICE';

    /**
     * @param string $type
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Collector\ShipmentDiscountCollectorInterface
     */
    public function resolveByType(string $type): ShipmentDiscountCollectorInterface;
}
