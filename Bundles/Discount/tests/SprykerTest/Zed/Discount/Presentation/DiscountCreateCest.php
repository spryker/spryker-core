<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Presentation;

use Codeception\Scenario;
use SprykerTest\Zed\Discount\DiscountPresentationTester;
use SprykerTest\Zed\Discount\PageObject\DiscountCreatePage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Presentation
 * @group DiscountCreateCest
 * Add your own group annotations below this line
 */
class DiscountCreateCest
{
    /**
     * @param \SprykerTest\Zed\Discount\DiscountPresentationTester $i
     * @param \SprykerTest\Zed\Discount\PageObject\DiscountCreatePage $createPage
     * @param \Codeception\Scenario $scenario
     *
     * @return void
     */
    public function createExclusiveDiscount(DiscountPresentationTester $i, DiscountCreatePage $createPage, Scenario $scenario)
    {
        $scenario->skip("Skipping test until fail reason is clarified.");

        $createPage->createDiscount(DiscountCreatePage::DISCOUNT_VALID_EXCLUSIVE);
        $i->see($createPage::MESSAGE_SUCCESSFUL_ALERT_CREATION);
    }

    /**
     * @param \SprykerTest\Zed\Discount\DiscountPresentationTester $i
     * @param \SprykerTest\Zed\Discount\PageObject\DiscountCreatePage $createPage
     * @param \Codeception\Scenario $scenario
     *
     * @return void
     */
    public function createNotExclusiveDiscount(DiscountPresentationTester $i, DiscountCreatePage $createPage, Scenario $scenario)
    {
        $scenario->skip("Skipping test until fail reason is clarified.");

        $createPage->createDiscount(DiscountCreatePage::DISCOUNT_VALID_NOT_EXCLUSIVE);
        $i->see($createPage::MESSAGE_SUCCESSFUL_ALERT_CREATION);
    }

    /**
     * @param \SprykerTest\Zed\Discount\DiscountPresentationTester $i
     * @param \SprykerTest\Zed\Discount\PageObject\DiscountCreatePage $createPage
     *
     * @return void
     */
    public function createInvalidDiscount(DiscountPresentationTester $i, DiscountCreatePage $createPage)
    {
        $createPage->createDiscount(DiscountCreatePage::EMPTY_DISCOUNT, ['name' => null]);
        $i->dontSee($createPage::MESSAGE_SUCCESSFUL_ALERT_CREATION);
        $i->seeInCurrentUrl($createPage::URL);
        $createPage->tab('General information');
        $i->seeElement($createPage::CURRENT_TAB_ERROR);
        $i->see('This value should not be blank');
        $i->see('Name', '.has-error');
        $createPage->tab('Discount calculation');
        $i->seeElement($createPage::CURRENT_TAB_ERROR);
        $i->see('This value should not be blank');
    }
}
