<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Category\Category\Zed;

use Acceptance\Category\Category\Zed\PageObject\CategoryCreatePage;
use Acceptance\Category\Category\Zed\PageObject\CategoryListPage;
use Acceptance\Category\Category\Zed\Tester\CategoryListTester;

/**
 * @group Acceptance
 * @group Category
 * @group Category
 * @group Zed
 * @group CategoryListCest
 */
class CategoryListCest
{

    /**
     * @param \Acceptance\Category\Category\Zed\Tester\CategoryListTester $i
     *
     * @return void
     */
    public function testICanSeeRootNodesList(CategoryListTester $i)
    {
        $i->amOnPage(CategoryListPage::URL);
        $i->seeElement(['class' => CategoryListPage::SELECTOR_TABLE]);
    }

    /**
     * @param \Acceptance\Category\Category\Zed\Tester\CategoryListTester $i
     *
     * @return void
     */
    public function testICanGoToCreateCategory(CategoryListTester $i)
    {
        $i->amOnPage(CategoryListPage::URL);
        $i->click(CategoryListPage::BUTTON_CREATE_CATEGORY);
        $i->amOnPage(CategoryCreatePage::URL);
    }

}
