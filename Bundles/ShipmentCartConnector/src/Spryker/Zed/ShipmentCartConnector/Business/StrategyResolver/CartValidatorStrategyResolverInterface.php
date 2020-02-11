<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business\StrategyResolver;

use Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartValidatorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
interface CartValidatorStrategyResolverInterface
{
    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartValidatorInterface
     */
    public function resolve(iterable $itemTransfers): ShipmentCartValidatorInterface;
}
