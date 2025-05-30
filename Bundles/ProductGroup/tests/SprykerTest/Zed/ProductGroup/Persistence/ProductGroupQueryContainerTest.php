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
use SprykerTest\Zed\ProductGroup\ProductGroupPersistenceTester;

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
    protected ProductGroupPersistenceTester $tester;

    /**
     * @return void
     */
    public function testQueryAllProductAbstractGroupsReturnCorrectQuery(): void
    {
        $productGroupQueryContainer = new ProductGroupQueryContainer();
        $productGroupQueryContainer->setFactory(new ProductGroupPersistenceFactory());
        $query = $productGroupQueryContainer->queryAllProductAbstractGroups();
//        $query = $this->tester->getFactory()->createProductGroupQuery()->queryAllProductAbstractGroups();

        $this->assertInstanceOf(SpyProductAbstractGroupQuery::class, $query);
    }

    /**
     * @return void
     */
    public function testQueryAllProductGroupsReturnCorrectQuery(): void
    {
        $productGroupQueryContainer = new ProductGroupQueryContainer();
        $productGroupQueryContainer->setFactory(new ProductGroupPersistenceFactory());
        $query = $productGroupQueryContainer->queryAllProductGroups();

        $this->assertInstanceOf(SpyProductGroupQuery::class, $query);
    }
}
