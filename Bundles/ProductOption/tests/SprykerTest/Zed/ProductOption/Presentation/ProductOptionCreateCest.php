<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Presentation;

use SprykerTest\Zed\ProductOption\PageObject\ProductOptionCreatePage;
use SprykerTest\Zed\ProductOption\ProductOptionPresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Presentation
 * @group ProductOptionCreateCest
 * Add your own group annotations below this line
 */
class ProductOptionCreateCest
{
    /**
     * @param \SprykerTest\Zed\ProductOption\ProductOptionPresentationTester $i
     *
     * @return void
     */
    public function testCreateProductOptionGroupWithOptionValues(ProductOptionPresentationTester $i)
    {
        $i->wantTo('Create single option group with one option');
        $i->expect('Option group with options created');

        $i->amOnPage(ProductOptionCreatePage::URL);

        $i->seeBreadcrumbNavigation('Dashboard / Products / Product Options / Create new Product Option');

        $optionGroupTestData = ProductOptionCreatePage::$productOptionGroupData[ProductOptionCreatePage::VALID_GROUP];

        $i->expandSecondTranslationBlock();

        $i->fillOptionGroupData($optionGroupTestData);
        $i->fillOptionValues($optionGroupTestData['values']);

        $translationToCopy = 'Translated value';
        $i->fillField('#product_option_general_groupNameTranslations_0_name', $translationToCopy);
        $i->click('//*[@id="product_option_general_groupNameTranslations_0"]/div/div/span/button');
        $copiedValue = $i->grabValueFrom('#product_option_general_groupNameTranslations_1_name');

        $i->assertSame($translationToCopy, $copiedValue, 'Value Successfully copied to other translation');

        $i->assignProducts();

        $i->unassignProduct();

        $i->submitProductGroupForm();

        $i->see(ProductOptionCreatePage::PRODUCT_OPTION_CREATED_SUCCESS_MESSAGE);
    }
}
