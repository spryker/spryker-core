<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementChecker;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointTransfer;

interface ProductOfferReplacementCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointTransfer $productOfferServicePointTransfer
     *
     * @return bool
     */
    public function isProductOfferServicePointReplaceable(ItemTransfer $itemTransfer, ProductOfferServicePointTransfer $productOfferServicePointTransfer): bool;
}
