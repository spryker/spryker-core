<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Presentation;

use SprykerTest\Zed\Category\CategoryPresentationTester;
use SprykerTest\Zed\Category\PageObject\CategoryCreatePage;
use SprykerTest\Zed\Category\PageObject\CategoryListPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Presentation
 * @group CategoryListCest
 * Add your own group annotations below this line
 */
class CategoryListCest
{
    /**
     * @param \SprykerTest\Zed\Category\CategoryPresentationTester $i
     *
     * @return void
     */
    public function testICanSeeRootNodesList(CategoryPresentationTester $i)
    {
        $i->amOnPage(CategoryListPage::URL);
        $i->seeElement(['class' => CategoryListPage::SELECTOR_TABLE]);
    }

    /**
     * @param \SprykerTest\Zed\Category\CategoryPresentationTester $i
     *
     * @return void
     */
    public function testICanGoToCreateCategoryPage(CategoryPresentationTester $i)
    {
        $i->amOnPage(CategoryListPage::URL);
        $i->click(CategoryListPage::BUTTON_CREATE_CATEGORY);
        $i->amOnPage(CategoryCreatePage::URL);
    }

    /**
     * @param \SprykerTest\Zed\Category\CategoryPresentationTester $i
     *
     * @return void
     */
    public function testThatICanSeeCategoryTreeForFirstRootNode(CategoryPresentationTester $i)
    {
        $i->amOnPage(CategoryListPage::URL);
        $i->waitForElement(CategoryListPage::SELECTOR_TREE_LIST);
    }
}
