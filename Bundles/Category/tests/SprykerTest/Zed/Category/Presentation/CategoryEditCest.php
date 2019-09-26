<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Presentation;

use SprykerTest\Zed\Category\CategoryPresentationTester;
use SprykerTest\Zed\Category\PageObject\CategoryEditPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Presentation
 * @group CategoryEditCest
 * Add your own group annotations below this line
 */
class CategoryEditCest
{
    /**
     * @param \SprykerTest\Zed\Category\CategoryPresentationTester $i
     *
     * @return void
     */
    public function testICanOpenEditCategoryPage(CategoryPresentationTester $i)
    {
        $categoryTransfer = $i->createCategory(CategoryEditPage::CATEGORY_A);
        $i->amOnPage(CategoryEditPage::getUrl($categoryTransfer->getIdCategory()));
        $i->wait(2);
        $i->canSee(CategoryEditPage::TITLE, 'h2');
        $i->seeInField(CategoryEditPage::FORM_FIELD_CATEGORY_KEY, CategoryEditPage::CATEGORY_A);
    }

    /**
     * @param \SprykerTest\Zed\Category\CategoryPresentationTester $i
     *
     * @return void
     */
    public function testICanEditCategoryCheckboxes(CategoryPresentationTester $i)
    {
        $categoryTransfer = $i->createCategory(CategoryEditPage::CATEGORY_A);
        $i->amOnPage(CategoryEditPage::getUrl($categoryTransfer->getIdCategory()));
        $i->wait(2);

        $i->cantSeeCheckboxIsChecked(CategoryEditPage::FORM_FIELD_CATEGORY_IS_ACTIVE);
        $i->seeCheckboxIsChecked(CategoryEditPage::FORM_FIELD_CATEGORY_IS_IN_MENU);
        $i->seeCheckboxIsChecked(CategoryEditPage::FORM_FIELD_CATEGORY_IS_SEARCHABLE);

        $i->click(['name' => CategoryEditPage::FORM_FIELD_CATEGORY_IS_ACTIVE]);
        $i->click(['name' => CategoryEditPage::FORM_FIELD_CATEGORY_IS_IN_MENU]);
        $i->click(['name' => CategoryEditPage::FORM_FIELD_CATEGORY_IS_SEARCHABLE]);

        $i->click(CategoryEditPage::SUBMIT_BUTTON);

        $i->amOnPage(CategoryEditPage::getUrl($categoryTransfer->getIdCategory()));
    }
}
