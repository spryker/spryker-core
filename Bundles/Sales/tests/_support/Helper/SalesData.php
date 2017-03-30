<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sales\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CheckoutResponseBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Testify\Helper\Locator;

class SalesData extends Module
{

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function haveOrder(array $override = [])
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem($override)
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->build();

        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->makeEmpty()->build();
        $this->getSalesFacade()->saveOrder($quoteTransfer, $checkoutResponseTransfer);

        return $checkoutResponseTransfer;
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    private function getSalesFacade()
    {
        return $this->getModule('\\' . Locator::class)->getLocator()->sales()->facade();
    }

}
