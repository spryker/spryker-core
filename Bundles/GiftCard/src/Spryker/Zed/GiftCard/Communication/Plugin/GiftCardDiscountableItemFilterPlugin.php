<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Communication\Plugin;

use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountableItemFilterPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface getFacade()
 * @method \Spryker\Zed\GiftCard\Communication\GiftCardCommunicationFactory getFactory()
 */
class GiftCardDiscountableItemFilterPlugin extends AbstractPlugin implements DiscountableItemFilterPluginInterface
{
    /**
     * Specification:
     *
     * This is additional filter applied to discountable items, the plugins are triggered after discount collectors run
     * this ensures that certain items are never picked by discount calculation and removed from DiscountableItem stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer
     */
    public function filter(CollectedDiscountTransfer $collectedDiscountTransfer)
    {
        return $this->getFacade()->filterGiftCardDiscountableItems($collectedDiscountTransfer);
    }
}
