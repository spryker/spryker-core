<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Category\Persistence;

use Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery;
use Spryker\Zed\Category\Persistence\CategoryQueryContainer;

/**
 * @group Spryker
 * @group Zed
 * @group Category
 * @group Persistence
 */
class CategoryQueryContainerTest extends \PHPUnit_Framework_TestCase
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
