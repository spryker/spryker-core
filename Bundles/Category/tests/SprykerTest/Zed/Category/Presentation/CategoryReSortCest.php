<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Presentation;

use SprykerTest\Zed\Category\CategoryPresentationTester;
use SprykerTest\Zed\Category\PageObject\Category;
use SprykerTest\Zed\Category\PageObject\CategoryReSortPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Presentation
 * @group CategoryReSortCest
 * Add your own group annotations below this line
 */
class CategoryReSortCest
{
    /**
     * @param \SprykerTest\Zed\Category\CategoryPresentationTester $i
     *
     * @return void
     */
    public function testThatICanSeeSubCategories(CategoryPresentationTester $i)
    {
        $i->amOnPage(CategoryReSortPage::URL);
        $i->waitForElement(CategoryReSortPage::SELECTOR_CATEGORY_LIST);
        $i->canSeeElement(CategoryReSortPage::SELECTOR_FIRST_SUB_CATEGORY);
    }

    /**
     * @param \SprykerTest\Zed\Category\CategoryPresentationTester $i
     *
     * @return void
     */
    public function testThatICanMoveCategories(CategoryPresentationTester $i)
    {
        $i->amOnPage(CategoryReSortPage::URL);
        $i->waitForElement(CategoryReSortPage::SELECTOR_CATEGORY_LIST);

        $firstItemName = $i->grabTextFrom(CategoryReSortPage::SELECTOR_FIRST_SUB_CATEGORY_NAME_CELL);

        $i->dragAndDrop(
            CategoryReSortPage::SELECTOR_FIRST_SUB_CATEGORY,
            CategoryReSortPage::SELECTOR_LAST_SUB_CATEGORY
        );
        $i->canSee($firstItemName, CategoryReSortPage::SELECTOR_LAST_SUB_CATEGORY);
    }

    /**
     * @param \SprykerTest\Zed\Category\CategoryPresentationTester $i
     *
     * @return void
     */
    public function testThatICanSaveReSortedSubCategories(CategoryPresentationTester $i)
    {
        $i->createCategory(Category::CATEGORY_A);

        $i->amOnPage(CategoryReSortPage::URL);
        $i->waitForElement(CategoryReSortPage::SELECTOR_CATEGORY_LIST);

        $lastItemName = $i->grabTextFrom(CategoryReSortPage::SELECTOR_LAST_SUB_CATEGORY_NAME_CELL);

        $i->dragAndDrop(
            CategoryReSortPage::SELECTOR_LAST_SUB_CATEGORY,
            CategoryReSortPage::SELECTOR_FIRST_SUB_CATEGORY
        );
        // This is necessary to move the category under oberservation to the first position in the list
        // Unfortunately dragAndDrop() doesn't move the dragged category to the first position so we have to move the
        // top category down.
        $i->dragAndDrop(
            CategoryReSortPage::SELECTOR_FIRST_SUB_CATEGORY,
            CategoryReSortPage::SELECTOR_SECOND_SUB_CATEGORY
        );
        $i->click(CategoryReSortPage::SELECTOR_SAVE_BUTTON);
        $i->waitForElement(CategoryReSortPage::SELECTOR_ALERT_BOX);
        $i->canSee('Success', CategoryReSortPage::SELECTOR_ALERT_BOX);

        $i->amOnPage(CategoryReSortPage::URL);
        $i->canSee($lastItemName, CategoryReSortPage::SELECTOR_FIRST_SUB_CATEGORY_NAME_CELL);
    }
}
