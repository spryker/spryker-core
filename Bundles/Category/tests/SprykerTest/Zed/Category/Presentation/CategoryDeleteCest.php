<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Presentation;

use SprykerTest\Zed\Category\CategoryPresentationTester;
use SprykerTest\Zed\Category\PageObject\CategoryListPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Presentation
 * @group CategoryDeleteCest
 * Add your own group annotations below this line
 */
class CategoryDeleteCest
{
    /**
     * @param \SprykerTest\Zed\Category\CategoryPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(CategoryPresentationTester $i)
    {
        $i->amOnPage(CategoryListPage::URL);
        $i->waitForElement(CategoryListPage::getDeleteButtonSelector());
        $i->click(CategoryListPage::getDeleteButtonSelector());
        $i->seeBreadcrumbNavigation('Dashboard / Category / Delete Category');
    }
}
