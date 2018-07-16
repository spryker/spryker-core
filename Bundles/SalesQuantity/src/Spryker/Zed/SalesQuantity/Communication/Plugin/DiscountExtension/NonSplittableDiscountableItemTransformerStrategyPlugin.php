<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Communication\Plugin\DiscountExtension;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountableItemTransformerTransfer;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountableItemTransformerStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesQuantity\Business\SalesQuantityFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesQuantity\SalesQuantityConfig getConfig()
 */
class NonSplittableDiscountableItemTransformerStrategyPlugin extends AbstractPlugin implements DiscountableItemTransformerStrategyPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     *
     * @return bool
     */
    public function isApplicable(DiscountableItemTransfer $discountableItemTransfer): bool
    {
        $originalItem = $discountableItemTransfer->getOriginalItem();

        if (!$originalItem) {
            return false;
        }

        return $this->getFacade()->isItemQuantitySplitRequired($originalItem);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransformerTransfer
     */
    public function transformDiscountableItem(
        DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
    ): DiscountableItemTransformerTransfer {
        return $this->getFacade()
            ->transformNonSplittableDiscountableItem($discountableItemTransformerTransfer);
    }
}
