<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Discount\Presentation;

use SprykerTest\Zed\Discount\PresentationTester;
use SprykerTest\Zed\Discount\Presentation\PageObject\DiscountCreatePage;

/**
 * Auto-generated group annotations
 * @group Discount
 * @group ZedPresentation
 * @group DiscountCreateCest
 * Add your own group annotations below this line
 */
class DiscountCreateCest
{

    /**
     * @param \SprykerTest\Zed\Discount\PresentationTester $i
     * @param \SprykerTest\Zed\Discount\Presentation\PageObject\DiscountCreatePage $createPage
     *
     * @return void
     */
    public function createExclusiveDiscount(PresentationTester $i, DiscountCreatePage $createPage)
    {
        $createPage->createDiscount(DiscountCreatePage::DISCOUNT_VALID_EXCLUSIVE);
        $i->see($createPage::MESSAGE_SUCCESSFUL_ALERT_CREATION);
    }

    /**
     * @param \SprykerTest\Zed\Discount\PresentationTester $i
     * @param \SprykerTest\Zed\Discount\Presentation\PageObject\DiscountCreatePage $createPage
     *
     * @return void
     */
    public function createNotExclusiveDiscount(PresentationTester $i, DiscountCreatePage $createPage)
    {
        $createPage->createDiscount(DiscountCreatePage::DISCOUNT_VALID_NOT_EXCLUSIVE);
        $i->see($createPage::MESSAGE_SUCCESSFUL_ALERT_CREATION);
    }

    /**
     * @param \SprykerTest\Zed\Discount\PresentationTester $i
     * @param \SprykerTest\Zed\Discount\Presentation\PageObject\DiscountCreatePage $createPage
     *
     * @return void
     */
    public function createInvalidDiscount(PresentationTester $i, DiscountCreatePage $createPage)
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
