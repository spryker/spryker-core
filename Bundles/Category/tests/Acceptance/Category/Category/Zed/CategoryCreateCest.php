<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Category\Category\Zed;

use Acceptance\Category\Category\Zed\PageObject\CategoryCreatePage;
use Acceptance\Category\Category\Zed\Tester\CategoryCreateTester;

/**
 * @group Acceptance
 * @group Category
 * @group Category
 * @group Zed
 * @group CategoryCreateCest
 */
class CategoryCreateCest
{

    /**
     * @param \Acceptance\Category\Category\Zed\Tester\CategoryCreateTester $i
     *
     * @return void
     */
    public function testICanCreateCategory(CategoryCreateTester $i)
    {
        $i->createCategory(CategoryCreatePage::CATEGORY_A);
        $i->waitForText(CategoryCreatePage::SUCCESS_MESSAGE, 10);
    }

}
