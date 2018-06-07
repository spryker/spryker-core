<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement\Presentation;

use SprykerTest\Zed\ProductManagement\PageObject\ProductManagementProductCreatePage;
use SprykerTest\Zed\ProductManagement\ProductManagementPresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductManagement
 * @group Presentation
 * @group ProductManagementProductCreateCest
 * Add your own group annotations below this line
 */
class ProductManagementProductCreateCest
{
    /**
     * @param \SprykerTest\Zed\ProductManagement\ProductManagementPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ProductManagementPresentationTester $i)
    {
        $i->registerProductManagementStoreRelationFormTypePlugin();
        $i->registerMoneyCollectionFormTypePlugin();

        $i->amOnPage(ProductManagementProductCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Products / Products / Create a Product');
    }
}
