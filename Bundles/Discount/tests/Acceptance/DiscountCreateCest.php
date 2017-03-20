<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Acceptance;

use Discount\PageObject\DiscountCreatePage;
use Discount\AcceptanceTester;

/**
 * @group Acceptance
 * @group Zed
 * @group DiscountCreateCest
 *
 * @group ZedLogin
 */
class DiscountCreateCest
{

    /**
     * @param \Discount\AcceptanceTester $i
     * @param \Discount\PageObject\DiscountCreatePage $createPage
     *
     * @return void
     */
    public function createExclusiveDiscount(AcceptanceTester $i, DiscountCreatePage $createPage)
    {
        $createPage->createDiscount(DiscountCreatePage::DISCOUNT_VALID_EXCLUSIVE);
        $i->see($createPage::MESSAGE_SUCCESSFUL_ALERT_CREATION);
    }

    /**
     * @param \Discount\AcceptanceTester $i
     * @param \Discount\PageObject\DiscountCreatePage $createPage
     *
     * @return void
     */
    public function createNotExclusiveDiscount(AcceptanceTester $i, DiscountCreatePage $createPage)
    {
        $createPage->createDiscount(DiscountCreatePage::DISCOUNT_VALID_NOT_EXCLUSIVE);
        $i->see($createPage::MESSAGE_SUCCESSFUL_ALERT_CREATION);
    }

    /**
     * @param \Discount\AcceptanceTester $i
     * @param \Discount\PageObject\DiscountCreatePage $createPage
     *
     * @return void
     */
    public function createInvalidDiscount(AcceptanceTester $i, DiscountCreatePage $createPage)
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

    /**
     * @param \Discount\AcceptanceTester $i
     * @param \Discount\PageObject\DiscountCreatePage $createPage
     *
     * @return void
     */
    public function simpleDiscountComputation(AcceptanceTester $i, DiscountCreatePage $createPage)
    {
        $createPage->open()->tab('Discount calculation');
        $createPage->fillInDiscountRule(0, 'item-price', 'equal', '12');
        $createPage->assertDiscountQuery("item-price = '12'");
        $i->click('Add rule');
        $createPage->fillInDiscountRule(1, 'item-quantity', 'greater', '2');
        $createPage->assertDiscountQuery("item-price = '12' AND item-quantity > '2'");
        $i->click('Delete', '#builder_calculation_rule_0');
        $createPage->assertDiscountQuery("item-quantity > '2'");
        $i->click('Add rule');
        $createPage->fillInDiscountRule(1, 'attribute.width', 'less or equal', '500');
        $createPage->changeDiscountGroupOperator('OR');
        $createPage->assertDiscountQuery("item-quantity > '2' OR attribute.width <= '500'");
    }

}
