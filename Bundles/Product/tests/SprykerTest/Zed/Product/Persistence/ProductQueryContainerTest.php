<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Persistence;

use Codeception\Test\Unit;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery;
use Spryker\Zed\Product\Persistence\ProductPersistenceFactory;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Persistence
 * @group ProductQueryContainerTest
 * Add your own group annotations below this line
 */
class ProductQueryContainerTest extends Unit
{
    /**
     * @return void
     */
    public function testQueryAllProductLocalizedAttributesReturnsCorrectQuery()
    {
        $productQueryContainer = new ProductQueryContainer();
        $productQueryContainer->setFactory(new ProductPersistenceFactory());
        $query = $productQueryContainer->queryAllProductLocalizedAttributes();

        $this->assertInstanceOf(SpyProductLocalizedAttributesQuery::class, $query);
    }

    /**
     * @return void
     */
    public function testQueryAllProductAbstractLocalizedAttributesReturnsCorrectQuery()
    {
        $productQueryContainer = new ProductQueryContainer();
        $productQueryContainer->setFactory(new ProductPersistenceFactory());
        $query = $productQueryContainer->queryAllProductAbstractLocalizedAttributes();

        $this->assertInstanceOf(SpyProductAbstractLocalizedAttributesQuery::class, $query);
    }
}
