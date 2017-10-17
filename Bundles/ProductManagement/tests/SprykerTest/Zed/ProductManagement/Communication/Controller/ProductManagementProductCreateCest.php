<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement\Communication\Controller;

use SprykerTest\Zed\ProductManagement\PageObject\ProductManagementProductCreatePage;
use SprykerTest\Zed\ProductManagement\ProductManagementCommunicationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductManagement
 * @group Communication
 * @group Controller
 * @group ProductManagementProductCreateCest
 * Add your own group annotations below this line
 */
class ProductManagementProductCreateCest
{
    /**
     * @param \SprykerTest\Zed\ProductManagement\ProductManagementCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ProductManagementCommunicationTester $i)
    {
        $i->amOnPage(ProductManagementProductCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Products / Products / Create a Product');
    }
}
