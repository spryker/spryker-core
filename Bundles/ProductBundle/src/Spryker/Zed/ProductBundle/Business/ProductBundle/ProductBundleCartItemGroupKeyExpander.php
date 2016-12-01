<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

use Generated\Shared\Transfer\CartChangeTransfer;

class ProductBundleCartItemGroupKeyExpander
{

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandExpandBundleItemGroupKey(CartChangeTransfer $cartChangeTransfer)
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }

            $groupKey = $itemTransfer->getGroupKey() . '_' . $itemTransfer->getRelatedBundleItemIdentifier();
            $itemTransfer->setGroupKey($groupKey);
        }

        return $cartChangeTransfer;
    }
}
