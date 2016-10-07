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
    public function testICanEditCategory(CategoryEditTester $i)
    {
        $categoryTransfer = $i->createCategory(CategoryEditPage::CATEGORY_A);
        $i->amOnPage(CategoryEditPage::getUrl($categoryTransfer->getIdCategory()));
        $i->canSee(CategoryEditPage::TITLE, 'h2');

    }

}
