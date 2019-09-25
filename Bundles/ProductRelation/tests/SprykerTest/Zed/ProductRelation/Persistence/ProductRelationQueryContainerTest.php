<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelation\Persistence;

use Codeception\Test\Unit;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationPersistenceFactory;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductRelation
 * @group Persistence
 * @group ProductRelationQueryContainerTest
 * Add your own group annotations below this line
 */
class ProductRelationQueryContainerTest extends Unit
{
    /**
     * @return void
     */
    public function testQueryProductRelationsReturnCorrectQuery()
    {
        $productRelationQueryContainer = new ProductRelationQueryContainer();
        $productRelationQueryContainer->setFactory(new ProductRelationPersistenceFactory());
        $query = $productRelationQueryContainer->queryAllProductRelations();

        $this->assertInstanceOf(SpyProductRelationQuery::class, $query);
    }
}
