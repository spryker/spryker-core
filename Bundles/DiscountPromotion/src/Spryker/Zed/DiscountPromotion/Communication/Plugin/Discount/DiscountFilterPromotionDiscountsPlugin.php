<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount;

use ArrayObject;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountableItemFilterPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacade getFacade()
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 */
class DiscountFilterPromotionDiscountsPlugin extends AbstractPlugin implements DiscountableItemFilterPluginInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer
     */
    public function filter(CollectedDiscountTransfer $collectedDiscountTransfer)
    {
        $discountPromotionTransfer = $collectedDiscountTransfer->getDiscount()->getDiscountPromotion();
        if ($discountPromotionTransfer) {
            return $collectedDiscountTransfer;
        }

        $discountableItems = new ArrayObject();
        foreach ($collectedDiscountTransfer->getDiscountableItems() as $itemTransfer) {
            if ($itemTransfer->getOriginalItem()->getIsPromotion()) {
                continue;
            }

            $discountableItems->append($itemTransfer);
        }

        $collectedDiscountTransfer->setDiscountableItems($discountableItems);

        return $collectedDiscountTransfer;
    }

}
