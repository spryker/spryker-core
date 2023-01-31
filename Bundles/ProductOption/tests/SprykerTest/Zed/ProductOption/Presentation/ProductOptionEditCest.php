<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Presentation;

use Spryker\Zed\ProductOption\Business\ProductOptionFacade;
use SprykerTest\Zed\ProductOption\PageObject\ProductOptionEditPage;
use SprykerTest\Zed\ProductOption\ProductOptionPresentationTester;

/**
 * Auto-generated group annotations
 *
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
     * @skip This test was temporarily skipped due to flikerness. See {@link https://spryker.atlassian.net/browse/CC-25718} for details
     *
     * @param \SprykerTest\Zed\ProductOption\ProductOptionPresentationTester $i
     *
     * @return void
     */
    public function testEditOptionWithMultipleValues(ProductOptionPresentationTester $i): void
    {
        $i->wantTo('Edit existing option group');
        $i->expect('Option group with options created');

        $productOptionGroupTransfer = $i->createProductOptionGroupTransfer();

        $idProductOptionGroup = $this->createProductOptionFacade()
            ->saveProductOptionGroup($productOptionGroupTransfer);

        $i->amOnPage(sprintf(ProductOptionEditPage::URL, (int)$idProductOptionGroup));

        $i->seeBreadcrumbNavigation('Catalog / Product Options / Edit Product Option');

        $idTaxSet = 2;
        $i->selectOption('#product_option_general_fkTaxSet', $idTaxSet);

        $i->submitProductGroupForm();
        $i->see(ProductOptionEditPage::PRODUCT_GROUP_EDIT_SUCCESS_MESSAGE);

        $i->assertEquals($idTaxSet, $i->grabValueFrom('#product_option_general_fkTaxSet'));

        $i->click('//*[@id="product_option_general_productOptionValues_0_prices_0_gross_amount"]');
        $i->submitProductGroupForm();
        $i->see(ProductOptionEditPage::PRODUCT_GROUP_EDIT_SUCCESS_MESSAGE);

        $updatedOptionValueNetAmount = '27.00';
        $updatedOptionValueGrossAmount = '32.00';
        $i->fillField('#product_option_general_productOptionValues_0_prices_0_net_amount', $updatedOptionValueNetAmount);
        $i->fillField('#product_option_general_productOptionValues_0_prices_0_gross_amount', $updatedOptionValueGrossAmount);
        $i->submitProductGroupForm();
        $i->assertEquals($updatedOptionValueNetAmount, $i->grabValueFrom('#product_option_general_productOptionValues_0_prices_0_net_amount'));
        $i->assertEquals($updatedOptionValueGrossAmount, $i->grabValueFrom('#product_option_general_productOptionValues_0_prices_0_gross_amount'));

        $i->wait(1);

        $i->assignProducts();
        $i->unassignProduct();

        $i->submitProductGroupForm();

        $i->wait(2);

        $i->waitForElement('//*[@id="page-wrapper"]/div[3]/div[2]/ul/li[2]');

        $i->wait(2);

        $i->clickWithLeftButton('//*[@id="page-wrapper"]/div[3]/div[2]/ul/li[2]', 20, 20);

        $i->waitAndClick('#assigned');

        $i->waitForElement('//*[@id="product-option-table"]/tbody/tr/td[1]');

        $idsPersisted = $i->grabMultiple('//*[@id="product-option-table"]/tbody/tr/td[1]');

        $i->assertGreaterThan(0, (int)$idsPersisted[0]);
        $i->assertGreaterThan(0, (int)$idsPersisted[1]);

        $i->waitAndClick('//*[@id="page-wrapper"]/div[2]/div[2]/div/form/button');
        $i->waitForElement('//*[@id="page-wrapper"]/div[3]/div[1]/div[1]/div');

        $i->canSee(ProductOptionEditPage::PRODUCT_GROUP_EDIT_ACTIVATE_SUCCESS_MESSAGE);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\ProductOptionFacade
     */
    protected function createProductOptionFacade(): ProductOptionFacade
    {
        return new ProductOptionFacade();
    }
}
