<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Category\Category\Zed;

use Acceptance\Category\Category\Zed\PageObject\CategoryEditPage;
use Acceptance\Category\Category\Zed\Tester\CategoryCreateTester;
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
        $categoryCreateTester = $i->haveFriend('categoryCreateTester', CategoryCreateTester::class);
        $categoryCreateTester->does(function (CategoryCreateTester $i) {
            $i->amZed();
            $i->amLoggedInUser();
            $i->createCategory(CategoryEditPage::CATEGORY_A);
        });

        $categoryEntity = $i->loadCategoryByCategoryKey(CategoryEditPage::CATEGORY_A);
        echo '<pre>' . PHP_EOL . \Symfony\Component\VarDumper\VarDumper::dump($categoryEntity) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();
    }

}
