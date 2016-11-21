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
        $product = ProductCategoryAssignPage::PRODUCTS[ProductCategoryAssignPage::PRODUCT_A];
        $availableProductCheckboxSelector = $this->buildProductSelector(
            $product,
            ProductCategoryAssignPage::AVAILABLE_PRODUCT_CHECKBOX_SELECTOR_PREFIX
        );

        $i->amOnPage(ProductCategoryAssignPage::URL);
        $i->waitForElement($availableProductCheckboxSelector);
        $i->checkOption($availableProductCheckboxSelector);
        $i->seeInField(
            ProductCategoryAssignPage::SELECTED_PRODUCTS_CSV_FIELD_SELECTOR,
            $product[ProductCategoryAssignPage::PRODUCT_ID]
        );
        $i->click(ProductCategoryAssignPage::FORM_SUBMIT_SELECTOR);
        $i->canSeeElement(ProductCategoryAssignPage::SUCCESS_MESSAGE_SELECTOR);

        $assignedProductCheckboxSelector = $this->buildProductSelector(
            $product,
            ProductCategoryAssignPage::ASSIGNED_PRODUCT_CHECKBOX_SELECTOR_PREFIX
        );
        $i->waitForElement($assignedProductCheckboxSelector);
    }

    /**
     * @after testThatICanAssignProducts
     *
     * @param \Acceptance\ProductCategory\ProductCategory\Zed\Tester\ProductCategoryAssignTester $i
     *
     * @return void
     */
    public function testThatICanDeassignProducts(ProductCategoryAssignTester $i)
    {
        $product = ProductCategoryAssignPage::PRODUCTS[ProductCategoryAssignPage::PRODUCT_A];
        $assignedProductCheckboxSelector = $this->buildProductSelector(
            $product,
            ProductCategoryAssignPage::ASSIGNED_PRODUCT_CHECKBOX_SELECTOR_PREFIX
        );

        $i->amOnPage(ProductCategoryAssignPage::URL);
        $i->waitForElement($assignedProductCheckboxSelector);
        $i->uncheckOption($assignedProductCheckboxSelector);
        $i->seeInField(
            ProductCategoryAssignPage::DESELECTED_PRODUCTS_CSV_FIELD_SELECTOR,
            $product[ProductCategoryAssignPage::PRODUCT_ID]
        );
        $i->click(ProductCategoryAssignPage::FORM_SUBMIT_SELECTOR);
        $i->canSeeElement(ProductCategoryAssignPage::SUCCESS_MESSAGE_SELECTOR);
    }

    /**
     * @param array $product
     * @param string $selectorPrefix
     *
     * @return string
     */
    private function buildProductSelector(array $product, $selectorPrefix)
    {
        return sprintf(
            '%s%s',
            $selectorPrefix,
            $product[ProductCategoryAssignPage::PRODUCT_ID]
        );
    }

}
