<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;

class ProductOptionCartQuantity implements ProductOptionCartQuantityInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeQuantity(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemQuantity = $itemTransfer->getQuantity();
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                 $productOptionTransfer->setQuantity($itemQuantity);
            }
        }

        return $quoteTransfer;
    }
}
