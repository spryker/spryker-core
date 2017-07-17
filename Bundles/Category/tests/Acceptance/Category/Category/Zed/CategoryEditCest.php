<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Category\Category\Zed;

use Acceptance\Category\Category\Zed\PageObject\CategoryEditPage;
use Acceptance\Category\Category\Zed\Tester\CategoryEditTester;

/**
 * @group Acceptance
 * @group Category
 * @group Category
 * @group Zed
 * @group CategoryEditCest
 */
class CategoryEditCest
{

    /**
     * @param \Acceptance\Category\Category\Zed\Tester\CategoryEditTester $i
     *
     * @return void
     */
    public function testICanOpenEditCategoryPage(CategoryEditTester $i)
    {
        $categoryTransfer = $i->createCategory(CategoryEditPage::CATEGORY_A);
        $i->amOnPage(CategoryEditPage::getUrl($categoryTransfer->getIdCategory()));
        $i->wait(2);
        $i->canSee(CategoryEditPage::TITLE, 'h2');
        $i->seeInField(CategoryEditPage::FORM_FIELD_CATEGORY_KEY, CategoryEditPage::CATEGORY_A);
    }

    /**
     * @param \Acceptance\Category\Category\Zed\Tester\CategoryEditTester $i
     *
     * @return void
     */
    public function testICanEditCategoryCheckboxes(CategoryEditTester $i)
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

//        $i->seeCheckboxIsChecked(CategoryEditPage::FORM_FIELD_CATEGORY_IS_ACTIVE);
//        $i->cantSeeCheckboxIsChecked(CategoryEditPage::FORM_FIELD_CATEGORY_IS_IN_MENU);
//        $i->cantSeeCheckboxIsChecked(CategoryEditPage::FORM_FIELD_CATEGORY_IS_CLICKABLE);
    }

}
