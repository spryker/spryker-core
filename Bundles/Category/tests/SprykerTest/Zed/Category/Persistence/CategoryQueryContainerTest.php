<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Category\Persistence;

use Codeception\Test\Unit;
use Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Spryker\Zed\Category\Persistence\CategoryQueryContainer;

/**
 * Auto-generated group annotations
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Category
 * @group Persistence
 * @group CategoryQueryContainerTest
 * Add your own group annotations below this line
 */
class CategoryQueryContainerTest extends Unit
{
    public const ID_CATEGORY_NODE = 23;

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

    /**
     * @return void
     */
    public function testQueryAllCategoryNodesReturnCorrectQuery()
    {
        $categoryQueryContainer = new CategoryQueryContainer();
        $query = $categoryQueryContainer->queryAllCategoryNodes();

        $this->assertInstanceOf(SpyCategoryNodeQuery::class, $query);
    }

    /**
     * @return void
     */
    public function testQueryAllCategoryAttributesReturnCorrectQuery()
    {
        $categoryQueryContainer = new CategoryQueryContainer();
        $query = $categoryQueryContainer->queryAllCategoryAttributes();

        $this->assertInstanceOf(SpyCategoryAttributeQuery::class, $query);
    }
}
