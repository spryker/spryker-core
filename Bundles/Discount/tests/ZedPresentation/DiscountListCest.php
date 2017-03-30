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
 */
class DiscountListCest
{
    /**
     * @param \ZedAcceptanceTester $i
     *
     * @return void
     */
    public function discountInList(ZedAcceptanceTester $i, DiscountEditPage $editPage, DiscountViewPage $viewPage)
    {
        $name = 'Works as test discount';
        $discountId = $i->haveDiscount(['displayName' => $name]);
        $firstTableRow = Locator::firstElement(DiscountListPage::DATA_TABLE_ROW);
        $i->amOnPage(DiscountListPage::URL);
        $i->waitForElementVisible($firstTableRow);
        $i->see($name, $firstTableRow);
        $i->see('Edit', $firstTableRow);
        $i->see('View', $firstTableRow);
        $i->see('Deactivate', $firstTableRow);
        $i->amGoingTo('open edit page for discount');
        $i->click('Edit', $firstTableRow);
        $i->seeInCurrentUrl($editPage->url($discountId));
        $i->see('Edit discount', 'h2');
        $i->amGoingTo('open view page for discount');
        $i->amOnPage(DiscountListPage::URL);
        $i->waitForElementVisible($firstTableRow);
        $i->click('View', $firstTableRow);
        $i->seeInCurrentUrl($viewPage->url($discountId));
        $i->see('View discount', 'h2');
        $i->see($name);
    }


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
