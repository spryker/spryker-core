<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductGroup\Persistence;

use Codeception\Test\Unit;
use Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery;
use Orm\Zed\ProductGroup\Persistence\SpyProductGroupQuery;
use Spryker\Zed\ProductGroup\Persistence\ProductGroupPersistenceFactory;
use Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductGroup
 * @group Persistence
 * @group ProductGroupQueryContainerTest
 * Add your own group annotations below this line
 */
class ProductGroupQueryContainerTest extends Unit
{
    /**
     * @return void
     */
    public function testQueryAllProductAbstractGroupsReturnCorrectQuery()
    {
        $productGroupQueryContainer = new ProductGroupQueryContainer();
        $productGroupQueryContainer->setFactory(new ProductGroupPersistenceFactory());
        $query = $productGroupQueryContainer->queryAllProductAbstractGroups();

        $this->assertInstanceOf(SpyProductAbstractGroupQuery::class, $query);
    }

    /**
     * @return void
     */
    public function testQueryAllProductGroupsReturnCorrectQuery()
    {
        $productGroupQueryContainer = new ProductGroupQueryContainer();
        $productGroupQueryContainer->setFactory(new ProductGroupPersistenceFactory());
        $query = $productGroupQueryContainer->queryAllProductGroups();

        $this->assertInstanceOf(SpyProductGroupQuery::class, $query);
    }
}
