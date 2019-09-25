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
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface getFacade()
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface getQueryContainer()
 */
class DiscountPromotionFilterCollectedItemsPlugin extends AbstractPlugin implements DiscountableItemFilterPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer
     */
    public function filter(CollectedDiscountTransfer $collectedDiscountTransfer)
    {
        if (!$collectedDiscountTransfer->getDiscount()) {
            return $collectedDiscountTransfer;
        }

        $discountPromotionTransfer = $collectedDiscountTransfer->getDiscount()->getDiscountPromotion();
        if ($discountPromotionTransfer) {
            return $collectedDiscountTransfer;
        }

        $discountableItems = new ArrayObject();
        foreach ($collectedDiscountTransfer->getDiscountableItems() as $itemTransfer) {
            if ($itemTransfer->getOriginalItem() && $itemTransfer->getOriginalItem()->getIdDiscountPromotion()) {
                continue;
            }

            $discountableItems->append($itemTransfer);
        }

        $collectedDiscountTransfer->setDiscountableItems($discountableItems);

        return $collectedDiscountTransfer;
    }
}
