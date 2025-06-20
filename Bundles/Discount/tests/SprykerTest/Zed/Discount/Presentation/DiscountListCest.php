<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Presentation;

use Codeception\Util\Locator;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
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
     * @var string
     */
    protected const CURRENCY_CODE = 'EUR';

    /**
     * @param \SprykerTest\Zed\Discount\DiscountPresentationTester $i
     *
     * @return void
     */
    public function _before(DiscountPresentationTester $i): void
    {
        $i->amZed();
        $i->amLoggedInUser();
    }

    /**
     * @param \SprykerTest\Zed\Discount\DiscountPresentationTester $i
     * @param \SprykerTest\Zed\Discount\PageObject\DiscountEditPage $editPage
     * @param \SprykerTest\Zed\Discount\PageObject\DiscountViewPage $viewPage
     *
     * @return void
     */
    public function showADiscountInList(DiscountPresentationTester $i, DiscountEditPage $editPage, DiscountViewPage $viewPage): void
    {
        $name = 'Works as test discount' . uniqid();
        $currencyTransfer = $i->haveCurrencyTransfer([CurrencyTransfer::CODE => static::CURRENCY_CODE]);
        $discount = $i->haveDiscount(['displayName' => $name], [
            [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);
        $i->amOnPage(DiscountListPage::URL);

        $firstTableRow = Locator::firstElement(DiscountListPage::DATA_TABLE_ROW);
        $i->waitForElementVisible($firstTableRow, 10);
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
        $i->wait(10);
        $i->see('View discount', 'h2');
        $i->see($name);
    }

    /**
     * @param \SprykerTest\Zed\Discount\DiscountPresentationTester $i
     *
     * @return void
     */
    public function testPageShouldShowList(DiscountPresentationTester $i): void
    {
        $i->amOnPage(DiscountListPage::URL);
        $i->seeElement(DiscountListPage::SELECTOR_DATA_TABLE);
    }
}
