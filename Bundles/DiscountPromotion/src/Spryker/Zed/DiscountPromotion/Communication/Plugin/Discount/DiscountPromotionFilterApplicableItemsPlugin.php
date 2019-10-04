<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountApplicableFilterPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface getFacade()
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface getQueryContainer()
 */
class DiscountPromotionFilterApplicableItemsPlugin extends AbstractPlugin implements DiscountApplicableFilterPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $discountApplicableItems
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function filter(array $discountApplicableItems, QuoteTransfer $quoteTransfer, $idDiscount)
    {
        $filteredItems = [];
        foreach ($discountApplicableItems as $itemTransfer) {
            if ($itemTransfer->getIdDiscountPromotion()) {
                continue;
            }
            $filteredItems[] = $itemTransfer;
        }

        return $filteredItems;
    }
}
