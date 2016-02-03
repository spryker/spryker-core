<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ItemProductOption implements CollectorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer, DiscountCollectorTransfer $discountCollectorTransfer)
    {
        $discountableOptions = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $discountableOptions[] = $productOptionTransfer;
            }
        }

        return $discountableOptions;
    }

}
