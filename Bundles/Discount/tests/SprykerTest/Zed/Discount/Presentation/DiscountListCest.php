<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Presentation;

use Codeception\Util\Locator;
use SprykerTest\Zed\Discount\DiscountPresentationTester;
use SprykerTest\Zed\Discount\PageObject\DiscountEditPage;
use SprykerTest\Zed\Discount\PageObject\DiscountListPage;
use SprykerTest\Zed\Discount\PageObject\DiscountViewPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Presentation
 * @group DiscountListCest
 * Add your own group annotations below this line
 */
class DiscountListCest
{
    /**
     * @param \SprykerTest\Zed\Discount\DiscountPresentationTester $i
     * @param \SprykerTest\Zed\Discount\PageObject\DiscountEditPage $editPage
     * @param \SprykerTest\Zed\Discount\PageObject\DiscountViewPage $viewPage
     *
     * @return void
     */
    public function showADiscountInList(DiscountPresentationTester $i, DiscountEditPage $editPage, DiscountViewPage $viewPage)
    {
        $name = 'Works as test discount';
        $discount = $i->haveDiscount(['displayName' => $name]);
        $i->amOnPage(DiscountListPage::URL);

        $firstTableRow = Locator::firstElement(DiscountListPage::DATA_TABLE_ROW);
        $i->waitForElementVisible($firstTableRow, 3);
        $i->see($name, $firstTableRow);
        $i->see('Edit', $firstTableRow);
        $i->see('View', $firstTableRow);
        $i->see('Deactivate', $firstTableRow);
        $i->amGoingTo('open edit page for discount');
        $i->click('Edit', $firstTableRow);
        $i->seeInCurrentUrl($editPage->url($discount->getIdDiscount()));
        $i->see('Edit discount', 'h2');
        $i->amGoingTo('open view page for discount');
        $i->amOnPage(DiscountListPage::URL);
        $i->waitForElementVisible($firstTableRow);
        $i->click('View', $firstTableRow);
        $i->seeInCurrentUrl($viewPage->url($discount->getIdDiscount()));
        $i->see('View discount', 'h2');
        $i->see($name);
    }

    /**
     * @param \SprykerTest\Zed\Discount\DiscountPresentationTester $i
     *
     * @return void
     */
    public function testPageShouldShowList(DiscountPresentationTester $i)
    {
        $i->amOnPage(DiscountListPage::URL);
        $i->seeElement(DiscountListPage::SELECTOR_DATA_TABLE);
    }
}
