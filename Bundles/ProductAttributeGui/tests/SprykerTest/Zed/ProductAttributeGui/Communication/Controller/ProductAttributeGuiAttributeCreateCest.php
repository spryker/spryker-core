<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAttributeGui\Communication\Controller;

use SprykerTest\Zed\ProductAttributeGui\PageObject\ProductAttributeGuiAttributeCreatePage;
use SprykerTest\Zed\ProductAttributeGui\ProductAttributeGuiCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductAttributeGui
 * @group Communication
 * @group Controller
 * @group ProductAttributeGuiAttributeCreateCest
 * Add your own group annotations below this line
 */
class ProductAttributeGuiAttributeCreateCest
{
    /**
     * @param \SprykerTest\Zed\ProductAttributeGui\ProductAttributeGuiCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ProductAttributeGuiCommunicationTester $i)
    {
        $i->amOnPage(ProductAttributeGuiAttributeCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Products / Attributes / Create a Product Attribute');
    }
}
