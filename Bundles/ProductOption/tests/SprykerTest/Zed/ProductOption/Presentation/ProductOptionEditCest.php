<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Presentation;

use Spryker\Zed\ProductOption\Business\ProductOptionFacade;
use SprykerTest\Zed\ProductOption\PageObject\ProductOptionEditPage;
use SprykerTest\Zed\ProductOption\PresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Presentation
 * @group ProductOptionEditCest
 * Add your own group annotations below this line
 */
class ProductOptionEditCest
{

    /**
     * @param \SprykerTest\Zed\ProductOption\PresentationTester $i
     *
     * @return void
     */
    public function testEditOptionWithMultipleValues(PresentationTester $i)
    {
        $i->wantTo('Edit existing option group');
        $i->expect('Option group with options created');

        $productOptionGroupTransfer = $i->createProductOptionGroupTransfer();

        $idProductOptionGroup = $this->createProductOptionFacade()
            ->saveProductOptionGroup($productOptionGroupTransfer);

        $i->amOnPage(sprintf(ProductOptionEditPage::URL, (int)$idProductOptionGroup));

        $i->seeBreadcrumbNavigation('Dashboard / Products / Product Options / Edit Product Option');

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
