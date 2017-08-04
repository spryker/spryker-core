<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Braintree\Presentation;

use SprykerTest\Yves\Braintree\BraintreePresentationTester;
use SprykerTest\Yves\Braintree\PageObject\ProductDetailPage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Braintree
 * @group Presentation
 * @group CreditCardGuestCheckoutCest
 * Add your own group annotations below this line
 */
class CreditCardGuestCheckoutCest
{

    /**
     * @skip Broken because of new checkout
     *
     * @param \SprykerTest\Yves\Braintree\BraintreePresentationTester $i
     *
     * @return void
     */
    public function creditCardCheckoutAsGuest(BraintreePresentationTester $i)
    {
        $i->wantToTest('That i can go through credit card checkout as guest');
        $i->addToCart(ProductDetailPage::URL);
        $i->checkoutWithCreditCardAsGuest();
    }

}
