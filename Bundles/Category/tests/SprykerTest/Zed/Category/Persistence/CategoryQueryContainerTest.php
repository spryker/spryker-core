<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Category\Persistence;

use Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\Category\Persistence\CategoryQueryContainer;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Category
 * @group Persistence
 * @group CategoryQueryContainerTest
 */
class CategoryQueryContainerTest extends PHPUnit_Framework_TestCase
{

    const ID_CATEGORY_NODE = 23;

    /**
     * @return void
     */
    public function testQueryClosureTableParentEntriesMustReturnExecutableQuery()
    {
        $categoryQueryContainer = new CategoryQueryContainer();
        $query = $categoryQueryContainer->queryClosureTableParentEntries(self::ID_CATEGORY_NODE);

        $query->findOne();
        $this->assertInstanceOf(SpyCategoryClosureTableQuery::class, $query);
    }

}
