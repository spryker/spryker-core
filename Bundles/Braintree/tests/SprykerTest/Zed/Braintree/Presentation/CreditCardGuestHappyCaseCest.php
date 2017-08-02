<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Braintree\Presentation;

use SprykerTest\Yves\Braintree\BraintreePresentationTester as CheckoutPresentationTester;
use SprykerTest\Yves\Braintree\PageObject\ProductDetailPage;
use SprykerTest\Zed\Braintree\BraintreePresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Braintree
 * @group Presentation
 * @group CreditCardGuestHappyCaseCest
 * Add your own group annotations below this line
 */
class CreditCardGuestHappyCaseCest
{

    /**
     * @skip Broken because of new checkout
     *
     * @param \SprykerTest\Zed\Braintree\BraintreePresentationTester $i
     *
     * @return void
     */
    public function closeCreditCardOrderHappyCase(BraintreePresentationTester $i)
    {
        $checkoutTester = $i->haveFriend('checkoutTester', BraintreeBraintreePresentationTester::class);
        $checkoutTester->does(function (CheckoutPresentationTester $i) {
            $i->addToCart(ProductDetailPage::URL);
            $i->checkoutWithCreditCardAsGuest();
        });
        $checkoutTester->leave();
        $i->wait(10);

        $i->amZed();
        $i->amLoggedInUser();
        $i->closeCreditCardOrderHappyCase();
    }

}
