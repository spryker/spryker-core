<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAttributeGui\Communication\Controller;

use SprykerTest\Zed\ProductAttributeGui\PageObject\ProductAttributeGuiAttributeListPage;
use SprykerTest\Zed\ProductAttributeGui\ProductAttributeGuiCommunicationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductAttributeGui
 * @group Communication
 * @group Controller
 * @group ProductAttributeGuiAttributeEditCest
 * Add your own group annotations below this line
 */
class ProductAttributeGuiAttributeEditCest
{
    /**
     * @param \SprykerTest\Zed\ProductAttributeGui\ProductAttributeGuiCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ProductAttributeGuiCommunicationTester $i)
    {
        $i->listDataTable(ProductAttributeGuiAttributeListPage::URL . '/table');
        $i->clickDataTableEditButton();
        $i->seeBreadcrumbNavigation('Dashboard / Products / Attributes / Edit Product Attribute');
    }
}
