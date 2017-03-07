<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Acceptance\ProductOption\Zed;

use Acceptance\ProductOption\Zed\PageObject\ProductOptionCreatePage;
use Acceptance\ProductOption\Zed\Tester\ProductOptionTest;

/**
 * @group Acceptance
 * @group ProductOption
 * @group Zed
 * @group ProductOptionCreateCest
 */
class ProductOptionCreateCest
{

    /**
     * @param \Acceptance\ProductOption\Zed\Tester\ProductOptionTest $i
     *
     * @return void
     */
    public function testCreateProductOptionGroupWithOptionValues(ProductOptionTest $i)
    {
        $i->wantTo('Create single option group with one option');
        $i->expect('Option group with options created');

        $i->amOnPage(ProductOptionCreatePage::URL);

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
