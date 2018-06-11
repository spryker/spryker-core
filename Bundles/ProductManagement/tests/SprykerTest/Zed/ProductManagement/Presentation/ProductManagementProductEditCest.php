<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement\Presentation;

use SprykerTest\Zed\ProductManagement\PageObject\ProductManagementProductListPage;
use SprykerTest\Zed\ProductManagement\ProductManagementPresentationTester;

/**
 * Auto-generated group annotations
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
     * @skip Fix me
     *
     * @param \SprykerTest\Zed\ProductManagement\ProductManagementPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ProductManagementPresentationTester $i)
    {
        $i->registerProductManagementStoreRelationFormTypePlugin();
        $i->registerMoneyCollectionFormTypePlugin();

        $i->listDataTable(ProductManagementProductListPage::URL . '/index/table');
        $i->clickDataTableEditButton();
        $i->seeBreadcrumbNavigation('Dashboard / Products / Products / Edit Product');
    }

    /**
     * @skip Fix me
     *
     * @param \SprykerTest\Zed\ProductManagement\ProductManagementPresentationTester $i
     *
     * @return void
     */
    public function canSaveProductWithoutEditing(ProductManagementPresentationTester $i)
    {
        $i->registerProductManagementStoreRelationFormTypePlugin();
        $i->registerMoneyCollectionFormTypePlugin();

        $i->listDataTable(ProductManagementProductListPage::URL . '/index/table');
        $i->clickDataTableEditButton();
        $i->seeBreadcrumbNavigation('Dashboard / Products / Products / Edit Product');
        $i->click('Save');
        $i->seeResponseCodeIs(200);
    }
}
