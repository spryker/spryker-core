<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\StrategyResolver;

use Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\ShipmentDiscountDecisionRuleInterface;

/**
 * @deprecated Will be removed in next major release.
 */
interface MultiShipmentDecisionRuleStrategyResolverInterface
{
    /**
     * @param string $type
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\DecisionRule\ShipmentDiscountDecisionRuleInterface
     */
    public function resolveByTypeAndItems(string $type, iterable $itemTransfers): ShipmentDiscountDecisionRuleInterface;
}
