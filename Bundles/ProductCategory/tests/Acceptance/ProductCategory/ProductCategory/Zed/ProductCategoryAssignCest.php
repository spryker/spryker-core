<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\ProductCategory\ProductCategory\Zed;

use Acceptance\ProductCategory\ProductCategory\Zed\PageObject\ProductCategoryAssignPage;
use Acceptance\ProductCategory\ProductCategory\Zed\Tester\ProductCategoryAssignTester;

/**
 * @group Acceptance
 * @group ProductCategory
 * @group ProductCategory
 * @group Zed
 * @group ProductCategoryAssignCest
 */
class ProductCategoryAssignCest
{

    /**
     * @param \Acceptance\ProductCategory\ProductCategory\Zed\Tester\ProductCategoryAssignTester $i
     *
     * @return void
     */
    public function testThatICanAssignProducts(ProductCategoryAssignTester $i)
    {
        $name = 'my_unique_product_name_' . rand(0,1000);

        $idAbstractProduct = $i->createProductEntity($name)->getIdProductAbstract();

        $i->amOnPage(ProductCategoryAssignPage::URL);
        $i->searchTableByProductName($name);

        $availableProductCheckboxSelector = $this->buildProductSelector(
            $idAbstractProduct,
            ProductCategoryAssignPage::AVAILABLE_PRODUCT_CHECKBOX_SELECTOR_PREFIX
        );

        $i->waitForElement($availableProductCheckboxSelector);
        $i->checkOption($availableProductCheckboxSelector);
        $i->seeInField(
            ProductCategoryAssignPage::SELECTED_PRODUCTS_CSV_FIELD_SELECTOR,
            $idAbstractProduct
        );
        $i->click(ProductCategoryAssignPage::FORM_SUBMIT_SELECTOR);
        $i->canSeeElement(ProductCategoryAssignPage::SUCCESS_MESSAGE_SELECTOR);

        $assignedProductCheckboxSelector = $this->buildProductSelector(
            $idAbstractProduct,
            ProductCategoryAssignPage::ASSIGNED_PRODUCT_CHECKBOX_SELECTOR_PREFIX
        );
        $i->waitForElement($assignedProductCheckboxSelector);
    }

    /**
     * @param \Acceptance\ProductCategory\ProductCategory\Zed\Tester\ProductCategoryAssignTester $i
     *
     * @return void
     */
    public function testThatICanDeassignProducts(ProductCategoryAssignTester $i)
    {
        $name = 'my_unique_product_name_' . rand(0,1000);
        $idAbstractProduct = $i->createProductEntity($name)->getIdProductAbstract();
        $i->assignProductToCategory(ProductCategoryAssignPage::ID_CATEGORY, $idAbstractProduct);

        $i->amOnPage(ProductCategoryAssignPage::URL);
        $i->searchTableByProductName($name);

        $assignedProductCheckboxSelector = $this->buildProductSelector(
            $idAbstractProduct,
            ProductCategoryAssignPage::ASSIGNED_PRODUCT_CHECKBOX_SELECTOR_PREFIX
        );

        $i->waitForElement($assignedProductCheckboxSelector);
        $i->uncheckOption($assignedProductCheckboxSelector);
        $i->seeInField(
            ProductCategoryAssignPage::DESELECTED_PRODUCTS_CSV_FIELD_SELECTOR,
            $idAbstractProduct
        );
        $i->click(ProductCategoryAssignPage::FORM_SUBMIT_SELECTOR);
        $i->canSeeElement(ProductCategoryAssignPage::SUCCESS_MESSAGE_SELECTOR);
    }

    /**
     * @param int $idAbstractProduct
     * @param string $selectorPrefix
     *
     * @return string
     */
    private function buildProductSelector($idAbstractProduct, $selectorPrefix)
    {
        return sprintf(
            '%s%s',
            $selectorPrefix,
            $idAbstractProduct
        );
    }

}
