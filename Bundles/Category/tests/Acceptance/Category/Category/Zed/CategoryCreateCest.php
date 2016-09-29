<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Category\Category\Zed;

use Acceptance\Category\Category\Zed\PageObject\CategoryCreatePage;
use Acceptance\Category\Category\Zed\Tester\CategoryListTester;

/**
 * @group Acceptance
 * @group Category
 * @group Category
 * @group Zed
 * @group CategoryListCest
 */
class CategoryCreateCest
{

    /**
     * @param \Acceptance\Category\Category\Zed\Tester\CategoryListTester $i
     *
     * @return void
     */
    public function testICanCreateCategory(CategoryListTester $i)
    {
        $i->amOnPage(CategoryCreatePage::URL);


        $category = CategoryCreatePage::CATEGORIES[CategoryCreatePage::CATEGORY_A];

        $i->fillField(CategoryCreatePage::FORM_FIELD_CATEGORY_KEY, $category[CategoryCreatePage::FORM_FIELD_CATEGORY_KEY]);
        $i->selectOption(CategoryCreatePage::FORM_FIELD_CATEGORY_PARENT, $category[CategoryCreatePage::FORM_FIELD_CATEGORY_PARENT]);

        $localizedAttributes = $category['attributes'];

        foreach ($localizedAttributes as $attributes) {
            $i->click($attributes['locale']);
        }

        $i->click(CategoryCreatePage::FORM_SUBMIT_BUTTON);

        $i->waitForText('The category was added successfully.', 10);
    }

}
