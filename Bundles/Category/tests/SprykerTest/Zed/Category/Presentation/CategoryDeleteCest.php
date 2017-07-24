<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Presentation;

use SprykerTest\Zed\Category\PageObject\CategoryListPage;
use SprykerTest\Zed\Category\PresentationTester;

/**
 * Auto-generated group annotations
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
     * @param \SprykerTest\Zed\Category\PresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(PresentationTester $i)
    {
        $i->amOnPage(CategoryListPage::URL);
        $i->wait(2);
        $i->click(CategoryListPage::getDeleteButtonSelector());
        $i->seeBreadcrumbNavigation('Dashboard / Category / Delete Category');
    }

}
