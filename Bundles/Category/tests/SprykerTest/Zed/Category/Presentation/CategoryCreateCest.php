<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Presentation;

use SprykerTest\Zed\Category\CategoryPresentationTester;
use SprykerTest\Zed\Category\PageObject\CategoryCreatePage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Presentation
 * @group CategoryCreateCest
 * Add your own group annotations below this line
 */
class CategoryCreateCest
{
    /**
     * @param \SprykerTest\Zed\Category\CategoryPresentationTester $i
     *
     * @return void
     */
    public function testICanCreateCategory(CategoryPresentationTester $i)
    {
        $i->amOnPage(CategoryCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Category / Create Category');
        $category = CategoryCreatePage::getCategorySelectorsWithValues(CategoryCreatePage::CATEGORY_A);
        $i->fillField(CategoryCreatePage::FORM_FIELD_CATEGORY_KEY, $category[CategoryCreatePage::FORM_FIELD_CATEGORY_KEY]);
        $i->selectOption(CategoryCreatePage::FORM_FIELD_CATEGORY_PARENT, $category[CategoryCreatePage::FORM_FIELD_CATEGORY_PARENT]);
        $i->selectOption(CategoryCreatePage::FORM_FIELD_CATEGORY_TEMPLATE, $category[CategoryCreatePage::FORM_FIELD_CATEGORY_TEMPLATE]);
        $localizedAttributes = $category['attributes'];

        foreach (CategoryCreatePage::CLOSED_IBOX_SELECTORS as $closedIboxSelector) {
            $i->click($closedIboxSelector);
        }

        foreach ($localizedAttributes as $locale => $attributes) {
            foreach ($attributes as $selector => $value) {
                $i->fillField(['name' => $selector], $value);
            }
        }
        $i->click(CategoryCreatePage::FORM_SUBMIT_BUTTON);

        $i->seeInPageSource(CategoryCreatePage::SUCCESS_MESSAGE);
    }
}
