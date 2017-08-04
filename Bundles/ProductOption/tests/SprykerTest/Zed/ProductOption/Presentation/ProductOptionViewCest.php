<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Presentation;

use SprykerTest\Zed\ProductOption\PageObject\ProductOptionListPage;
use SprykerTest\Zed\ProductOption\ProductOptionPresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Presentation
 * @group ProductOptionViewCest
 * Add your own group annotations below this line
 */
class ProductOptionViewCest
{

    /**
     * @param \SprykerTest\Zed\ProductOption\ProductOptionPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ProductOptionPresentationTester $i)
    {
        $i->amOnPage(ProductOptionListPage::URL);
        $i->clickDataTableViewButton();
        $i->seeBreadcrumbNavigation('Dashboard / Products / Product Options / View Product Option');
    }

}
