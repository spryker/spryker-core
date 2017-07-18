<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Category\Category\Zed\Tester;

use Acceptance\Category\Category\Zed\PageObject\CategoryCreatePage;
use Category\ZedAcceptanceTester;

class CategoryCreateTester extends ZedAcceptanceTester
{

    /**
     * @param string $categoryName
     *
     * @return void
     */
    public function createCategory($categoryName)
    {
        $i = $this;
        $i->amOnPage(CategoryCreatePage::URL);

        $category = CategoryCreatePage::getCategorySelectorsWithValues($categoryName);
        $i->fillField(CategoryCreatePage::FORM_FIELD_CATEGORY_KEY, $category[CategoryCreatePage::FORM_FIELD_CATEGORY_KEY]);
        $i->selectOption(CategoryCreatePage::FORM_FIELD_CATEGORY_PARENT, $category[CategoryCreatePage::FORM_FIELD_CATEGORY_PARENT]);
        $i->selectOption(CategoryCreatePage::FORM_FIELD_CATEGORY_TEMPLATE, $category[CategoryCreatePage::FORM_FIELD_CATEGORY_TEMPLATE]);

        $localizedAttributes = $category['attributes'];

        foreach ($localizedAttributes as $locale => $attributes) {
            foreach ($attributes as $selector => $value) {
                $i->fillField(['name' => $selector], $value);
            }
        }

        $i->click(CategoryCreatePage::FORM_SUBMIT_BUTTON);
    }

}
