<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Communication\Plugin\DiscountExtension;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\Distributor\DiscountableItemTransformerStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class NonSplittableDiscountableItemTransformerStrategyPlugin extends AbstractPlugin implements DiscountableItemTransformerStrategyPluginInterface
{
    /**
     * @var float
     */
    protected $roundingError = 0.0;

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     *
     * @return bool
     */
    public function isApplicable(DiscountableItemTransfer $discountableItemTransfer): bool
    {
        return !$discountableItemTransfer->getOriginalItem()->getIsQuantitySplittable();
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param int $totalDiscountAmount
     * @param int $totalAmount
     * @param int $quantity
     *
     * @return void
     */
    public function transformDiscountableItem(
        DiscountableItemTransfer $discountableItemTransfer,
        DiscountTransfer $discountTransfer,
        int $totalDiscountAmount,
        int $totalAmount,
        int $quantity
    ): void {
        $this->getFacade()
            ->transformDiscountableItem($discountableItemTransfer, $discountTransfer, $totalDiscountAmount, $totalAmount, $quantity);
    }
}
