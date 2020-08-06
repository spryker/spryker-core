<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement\Presentation;

use PHPUnit\Framework\Assert;
use SprykerTest\Zed\ProductManagement\PageObject\ProductManagementProductListPage;
use SprykerTest\Zed\ProductManagement\ProductManagementPresentationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductManagement
 * @group Presentation
 * @group ProductManagementProductEditCest
 * Add your own group annotations below this line
 */
class ProductManagementProductEditCest
{
    /**
     * @param \SprykerTest\Zed\ProductManagement\ProductManagementPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ProductManagementPresentationTester $i): void
    {
        $i->registerProductManagementStoreRelationFormTypePlugin();
        $i->registerMoneyCollectionFormTypePlugin();

        $i->amOnPage(ProductManagementProductListPage::URL);

        $i->waitForElementVisible('.dataTables_scrollBody');

        $i->clickDataTableEditButton();
        $i->seeBreadcrumbNavigation('Catalog / Products / Edit Product');
    }

    /**
     * @param \SprykerTest\Zed\ProductManagement\ProductManagementPresentationTester $i
     *
     * @return void
     */
    public function canSaveProductWithoutEditing(ProductManagementPresentationTester $i): void
    {
        Assert::markTestSkipped('Re-enable when GLUE-9675 is done');
        $i->registerProductManagementStoreRelationFormTypePlugin();
        $i->registerMoneyCollectionFormTypePlugin();

        $i->amOnPage(ProductManagementProductListPage::URL);

        $i->waitForElementVisible('.dataTables_scrollBody');

        $i->clickDataTableEditButton();
        $i->seeBreadcrumbNavigation('Catalog / Products / Edit Product');
        $i->click('Save');

        $i->waitForJS('return document.readyState == "complete"');

        $alertText = $i->grabTextFrom('.alert-success');

        $i->assertNotEmpty($alertText);
    }
}
