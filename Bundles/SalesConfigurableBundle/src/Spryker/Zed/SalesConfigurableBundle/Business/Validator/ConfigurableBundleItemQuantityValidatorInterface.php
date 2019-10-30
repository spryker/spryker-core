<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business\Validator;

use Generated\Shared\Transfer\ItemTransfer;

interface ConfigurableBundleItemQuantityValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isConfigurableBundleItemQuantitySplittable(ItemTransfer $itemTransfer): bool;
}
