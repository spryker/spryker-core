<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class Item implements CollectorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return array
     */
    public function collect(QuoteTransfer $quoteTransfer, DiscountCollectorTransfer $discountCollectorTransfer)
    {
        $discountableItems = [];

        foreach ($quoteTransfer->getItems() as $item) {
            $discountableItems[] = $item;
        }

        return $discountableItems;
    }

}
