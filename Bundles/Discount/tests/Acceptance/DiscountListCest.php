<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Acceptance\Discount\Zed;

use Discount\PageObject\DiscountListPage;
use Discount\AcceptanceTester;

/**
 * @group Acceptance
 * @group Discount
 * @group Zed
 * @group DiscountListCest
 * @group ZedLogin
 */
class DiscountListCest
{

    /**
     * @param \Discount\AcceptanceTester $i
     *
     * @return void
     */
    public function testPageShouldShowList(AcceptanceTester $i)
    {
        $i->wantTo('See a list of created discounts');
        $i->expect('A grid with discounts is shown');

        $i->amOnPage(DiscountListPage::URL);
        $i->seeElement(DiscountListPage::SELECTOR_DATA_TABLE);
    }

}
