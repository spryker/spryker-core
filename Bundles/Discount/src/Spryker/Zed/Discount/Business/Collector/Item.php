<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class Item implements CollectorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
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
