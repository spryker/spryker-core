<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Braintree\Presentation;

use Acceptance\Braintree\Checkout\Yves\PageObject\ProductDetailPage;
use Acceptance\Braintree\Checkout\Yves\Tester\CheckoutTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Braintree
 * @group Presentation
 * @group PayPalGuestCheckoutCest
 * Add your own group annotations below this line
 */
class PayPalGuestCheckoutCest
{

    /**
     * @skip Broken because of new checkout
     *
     * @param \Acceptance\Braintree\Checkout\Yves\Tester\CheckoutTester $i
     *
     * @return void
     */
    public function testPayPalCheckoutAsGuest(CheckoutTester $i)
    {
        $i->wantToTest('That i can go through paypal checkout as guest');
        $i->addToCart(ProductDetailPage::URL);
        $i->checkoutWithPayPalAsGuest();
    }

}
