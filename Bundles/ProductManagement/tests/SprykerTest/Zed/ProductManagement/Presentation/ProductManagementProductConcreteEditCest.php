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
 * @group ProductManagementProductConcreteEditCest
 * Add your own group annotations below this line
 */
class ProductManagementProductConcreteEditCest
{
    /**
     * @param \SprykerTest\Zed\ProductManagement\ProductManagementPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ProductManagementPresentationTester $i)
    {
        $i->registerMoneyCollectionFormTypePlugin();

        $i->amOnPage(ProductManagementProductListPage::URL);
        $i->clickDataTableViewButton();
        $i->clickDataTableEditButton(1, 'product-variant-table_wrapper');
        $i->seeBreadcrumbNavigation('Dashboard / Products / Products / Edit Concrete Product');
    }
}
