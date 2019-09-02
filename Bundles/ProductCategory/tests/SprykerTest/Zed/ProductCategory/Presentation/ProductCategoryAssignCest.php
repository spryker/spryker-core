<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategory\Presentation;

use SprykerTest\Zed\ProductCategory\PageObject\ProductCategoryAssignPage;
use SprykerTest\Zed\ProductCategory\ProductCategoryPresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductCategory
 * @group Presentation
 * @group ProductCategoryAssignCest
 * Add your own group annotations below this line
 */
class ProductCategoryAssignCest
{
    /**
     * @param \SprykerTest\Zed\ProductCategory\ProductCategoryPresentationTester $i
     *
     * @return void
     */
    public function testThatICanAssignProducts(ProductCategoryPresentationTester $i)
    {
        $name = 'my_unique_product_name_' . sha1(random_bytes(50));

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
        $i->waitForElement(ProductCategoryAssignPage::SUCCESS_MESSAGE_SELECTOR);

        $assignedProductCheckboxSelector = $this->buildProductSelector(
            $idAbstractProduct,
            ProductCategoryAssignPage::ASSIGNED_PRODUCT_CHECKBOX_SELECTOR_PREFIX
        );
        $i->waitForElement($assignedProductCheckboxSelector);
    }

    /**
     * @param \SprykerTest\Zed\ProductCategory\ProductCategoryPresentationTester $i
     *
     * @return void
     */
    public function testThatICanDeassignProducts(ProductCategoryPresentationTester $i)
    {
        $name = 'my_unique_product_name_' . sha1(random_bytes(50));
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
        $i->waitForElement(ProductCategoryAssignPage::SUCCESS_MESSAGE_SELECTOR);
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
