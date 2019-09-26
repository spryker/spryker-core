<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSet\Persistence;

use Codeception\Test\Unit;
use Orm\Zed\ProductSet\Persistence\SpyProductSetDataQuery;
use Spryker\Zed\ProductSet\Persistence\ProductSetPersistenceFactory;
use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductSet
 * @group Persistence
 * @group ProductSetQueryContainerTest
 * Add your own group annotations below this line
 */
class ProductSetQueryContainerTest extends Unit
{
    /**
     * @return void
     */
    public function testQueryAllProductSetDataReturnsCorrectQuery()
    {
        $productSetQueryContainer = new ProductSetQueryContainer();
        $productSetQueryContainer->setFactory(new ProductSetPersistenceFactory());
        $query = $productSetQueryContainer->queryAllProductSetData();

        $this->assertInstanceOf(SpyProductSetDataQuery::class, $query);
    }
}
