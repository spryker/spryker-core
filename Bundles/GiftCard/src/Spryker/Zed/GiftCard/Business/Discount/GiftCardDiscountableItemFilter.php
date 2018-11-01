<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Discount;

use ArrayObject;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;

class GiftCardDiscountableItemFilter implements GiftCardDiscountableItemFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer
     */
    public function filterGiftCardDiscountableItems(CollectedDiscountTransfer $collectedDiscountTransfer)
    {
        $discountableItems = new ArrayObject();

        foreach ($collectedDiscountTransfer->getDiscountableItems() as $discountableItem) {
            if ($this->isGiftCard($discountableItem)) {
                continue;
            }

            $discountableItems[] = $discountableItem;
        }

        $collectedDiscountTransfer->setDiscountableItems($discountableItems);

        return $collectedDiscountTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     *
     * @return bool
     */
    protected function isGiftCard(DiscountableItemTransfer $discountableItemTransfer)
    {
        $originalItem = $discountableItemTransfer->getOriginalItem();

        if (!$originalItem) {
            return false;
        }

        $giftCardMetadata = $originalItem->getGiftCardMetadata();

        if (!$giftCardMetadata) {
            return false;
        }

        return $giftCardMetadata->getIsGiftCard();
    }
}
