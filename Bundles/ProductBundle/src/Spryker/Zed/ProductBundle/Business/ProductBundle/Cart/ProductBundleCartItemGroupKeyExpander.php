<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class ProductBundleCartItemGroupKeyExpander implements ProductBundleCartItemGroupKeyExpanderInterface
{

    const GROUP_KEY_DELIMITER = '_';

    /**
     * @var array
     */
    protected $skuMap = [];

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

            $itemTransfer->requireGroupKey();

            $groupKey = $this->buildGroupKey($itemTransfer);
            $itemTransfer->setGroupKey($groupKey);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function buildGroupKey(ItemTransfer $itemTransfer)
    {
        if (!isset($this->skuMap[$itemTransfer->getSku()])) {
            $this->skuMap[$itemTransfer->getSku()] = 1;
        } else {
            $this->skuMap[$itemTransfer->getSku()]++;
        }

        return $itemTransfer->getGroupKey() . static::GROUP_KEY_DELIMITER . $itemTransfer->getRelatedBundleItemIdentifier() . $this->skuMap[$itemTransfer->getSku()];
    }

}
