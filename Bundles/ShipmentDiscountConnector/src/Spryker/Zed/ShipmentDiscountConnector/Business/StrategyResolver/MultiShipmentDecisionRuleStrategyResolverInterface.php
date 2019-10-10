<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\StrategyResolver;

use Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountDecisionRuleInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
interface MultiShipmentDecisionRuleStrategyResolverInterface
{
    /**
     * @param string $type
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Spryker\Zed\ShipmentDiscountConnector\Business\Model\ShipmentDiscountDecisionRuleInterface
     */
    public function resolveByTypeAndItems(string $type, iterable $itemTransfers): ShipmentDiscountDecisionRuleInterface;
}
