<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace ZedPresentation\Discount\Zed;

use Discount\PageObject\DiscountListPage;
use Discount\ZedPresentationTester;

/**
 * @group Acceptance
 * @group Zed
 * @group DiscountListCest
 *
 * @group ZedLogin
 */
class DiscountListCest
{

    /**
     * @param \Discount\ZedPresentationTester $i
     *
     * @return void
     */
    public function testPageShouldShowList(ZedPresentationTester $i)
    {
        $i->wantTo('See a list of created discounts');
        $i->expect('A grid with discounts is shown');

        $i->amOnPage(DiscountListPage::URL);
        $i->seeElement(DiscountListPage::SELECTOR_DATA_TABLE);
    }

}
