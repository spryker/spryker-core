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
    public function testICanSeeCategoryTree(CategoryListTester $i)
    {
        $i->amOnPage(CategoryListPage::URL);
        $i->wait(5);
        $i->seeElement(['id' => CategoryListPage::SELECTOR_CATEGORIES_LIST]);
    }

    /**
     * @param \Acceptance\Category\Category\Zed\Tester\CategoryListTester $i
     *
     * @return void
     */
    public function testICanAddCategory(CategoryListTester $i)
    {
        $i->amOnPage(CategoryListPage::URL);
        $i->click(CategoryListPage::BUTTON_CREATE_CATEGORY);
        $i->amOnPage(CategoryCreatePage::URL);

        $i->fillField('category[category_key]', 'new-category-key');
        $i->selectOption('category[fk_parent_category_node]', 1);

        $i->click('Create');

        $i->waitForText('The category was added successfully.', 10);
    }

}
