<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Presentation;

use SprykerTest\Zed\ProductOption\PageObject\ProductOptionListPage;
use SprykerTest\Zed\ProductOption\PresentationTester;

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
     * @param \SprykerTest\Zed\ProductOption\PresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(PresentationTester $i)
    {
        $i->amOnPage(ProductOptionListPage::URL);
        $i->wait(2);
        $i->click('(//a[contains(., "View")])[1]');
        $i->seeBreadcrumbNavigation('Dashboard / Products / Product Options / View Product Option');
    }

}
