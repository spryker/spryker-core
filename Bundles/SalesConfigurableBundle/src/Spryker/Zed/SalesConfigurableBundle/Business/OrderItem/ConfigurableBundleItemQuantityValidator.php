<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business\OrderItem;

use Generated\Shared\Transfer\ItemTransfer;

class ConfigurableBundleItemQuantityValidator implements ConfigurableBundleItemQuantityValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isConfigurableBundleItemQuantitySplittable(ItemTransfer $itemTransfer): bool
    {
        if (!method_exists($itemTransfer, 'getConfiguredBundle')) {
            return false;
        }

        if ($itemTransfer->getConfiguredBundle()) {
            return true;
        }

        return false;
    }
}
