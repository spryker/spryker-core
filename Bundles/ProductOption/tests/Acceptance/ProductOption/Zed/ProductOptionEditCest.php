<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\ProductOption\Zed;

use Acceptance\ProductOption\Zed\PageObject\ProductOptionEditPage;
use Acceptance\ProductOption\Zed\Tester\ProductOptionTest;
use Spryker\Zed\ProductOption\Business\ProductOptionFacade;

/**
 * @group Acceptance
 * @group ProductOption
 * @group Zed
 * @group ProductOptionEditCest
 */
class ProductOptionEditCest
{

    /**
     * @param \Acceptance\ProductOption\Zed\Tester\ProductOptionTest $i
     *
     * @return void
     */
    public function testEditOptionWithMultipleValues(ProductOptionTest $i)
    {
        $i->wantTo('Edit existing option group');
        $i->expect('Option group with options created');

        $productOptionGroupTransfer = $i->createProductOptionGroupTransfer();

        $idProductOptionGroup = $this->createProductOptionFacade()
            ->saveProductOptionGroup($productOptionGroupTransfer);

        $i->amOnPage(sprintf(ProductOptionEditPage::URL, (int)$idProductOptionGroup));

        $idTaxSet = 2;
        $i->selectOption('#product_option_general_fkTaxSet', $idTaxSet);

        $i->submitProductGroupForm();
        $i->see(ProductOptionEditPage::PRODUCT_GROUP_EDIT_SUCCESS_MESSAGE);

        $i->assertEquals($idTaxSet, $i->grabValueFrom('#product_option_general_fkTaxSet'));

        $i->click('//*[@id="product_option_general_productOptionValues_1"]/div[4]/div/input');
        $i->submitProductGroupForm();
        $i->see(ProductOptionEditPage::PRODUCT_GROUP_EDIT_SUCCESS_MESSAGE);

        $updatedOptionValueAmount = 25.00;
        $i->fillField('#product_option_general_productOptionValues_0_price', $updatedOptionValueAmount);
        $i->submitProductGroupForm();
        $i->assertEquals($updatedOptionValueAmount, $i->grabValueFrom('#product_option_general_productOptionValues_0_price'));

        $i->wait(1);

        $i->assignProducts();
        $i->unassignProduct();

        $i->submitProductGroupForm();

        $i->selectProductTab();

        $i->wait(2);

        $i->click('#assigned');

        $idsPersisted = $i->grabMultiple('//*[@id="product-option-table"]/tbody/tr/td[1]');

        $i->assertGreaterThan(0, (int)$idsPersisted[0]);
        $i->assertGreaterThan(0, (int)$idsPersisted[1]);

        $i->click('//*[@id="page-wrapper"]/div[2]/div[2]/div/a[1]');
        $i->canSee(ProductOptionEditPage::PRODUCT_GROUP_EDIT_ACTIVATE_SUCCESS_MESSAGE);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\ProductOptionFacade
     */
    protected function createProductOptionFacade()
    {
        return new ProductOptionFacade();
    }

}
