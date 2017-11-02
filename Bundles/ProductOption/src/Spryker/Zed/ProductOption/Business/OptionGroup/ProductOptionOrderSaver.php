<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface;
use Spryker\Zed\ProductOption\Business\PlaceOrder\ProductOptionOrderSaver as BaseProductOptionOrderSaver;

/**
 * @deprecated Use Spryker\Zed\ProductOption\Business\PlaceOrder\ProductOptionOrderSaver instead
 * Will be removed will the next major release
 */
class ProductOptionOrderSaver extends BaseProductOptionOrderSaver implements ProductOptionOrderSaverInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function save(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $saveOrderTransfer = $checkoutResponse->getSaveOrder();
        $this->saveOrder($quoteTransfer, $saveOrderTransfer);
    }
}
