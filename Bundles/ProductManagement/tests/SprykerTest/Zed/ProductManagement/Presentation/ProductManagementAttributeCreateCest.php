<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement\Presentation;

use SprykerTest\Zed\ProductManagement\PageObject\ProductManagementAttributeCreatePage;
use SprykerTest\Zed\ProductManagement\PresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductManagement
 * @group Presentation
 * @group ProductManagementAttributeCreateCest
 * Add your own group annotations below this line
 */
class ProductManagementAttributeCreateCest
{

    /**
     * @param \SprykerTest\Zed\ProductManagement\PresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(PresentationTester $i)
    {
        $i->amOnPage(ProductManagementAttributeCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Products / Attributes / Create a Product Attribute');
    }

}
