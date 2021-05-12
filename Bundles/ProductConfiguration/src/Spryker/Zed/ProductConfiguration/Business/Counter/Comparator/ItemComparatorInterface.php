<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business\Counter\Comparator;

use Generated\Shared\Transfer\ItemTransfer;

interface ItemComparatorInterface
{
    /**
     * Specification
     * - Checks if all configured item property values are same (see ProductConfigurationConfig->getItemFieldsForIsSameItemComparison())
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemInCartTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @throws \Spryker\Zed\ProductConfiguration\Business\Exception\TransferPropertyNotFoundException
     *
     * @return bool
     */
    public function isSameItem(
        ItemTransfer $itemInCartTransfer,
        ItemTransfer $itemTransfer
    ): bool;
}
