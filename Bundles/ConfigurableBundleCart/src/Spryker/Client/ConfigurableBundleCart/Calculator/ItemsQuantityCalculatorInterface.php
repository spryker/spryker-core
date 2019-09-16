<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Calculator;

use ArrayObject;

interface ItemsQuantityCalculatorInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param int $configuredBundleQuantity
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function updateItemsQuantity(ArrayObject $itemTransfers, int $configuredBundleQuantity): ArrayObject;
}
