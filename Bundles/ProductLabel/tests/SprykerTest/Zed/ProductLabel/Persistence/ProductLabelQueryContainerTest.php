<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabel\Persistence;

use Codeception\Test\Unit;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelPersistenceFactory;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductLabel
 * @group Persistence
 * @group ProductLabelQueryContainerTest
 * Add your own group annotations below this line
 */
class ProductLabelQueryContainerTest extends Unit
{
    /**
     * @return void
     */
    public function testQueryAllLocalizedAttributesLabelsReturnsCorrectQuery()
    {
        $productLabelQueryContainer = new ProductLabelQueryContainer();
        $productLabelQueryContainer->setFactory(new ProductLabelPersistenceFactory());
        $query = $productLabelQueryContainer->queryAllLocalizedAttributesLabels();

        $this->assertInstanceOf(SpyProductLabelLocalizedAttributesQuery::class, $query);
    }

    /**
     * @return void
     */
    public function testQueryAllProductLabelProductAbstractRelationsReturnsCorrectQuery()
    {
        $productLabelQueryContainer = new ProductLabelQueryContainer();
        $productLabelQueryContainer->setFactory(new ProductLabelPersistenceFactory());
        $query = $productLabelQueryContainer->queryAllProductLabelProductAbstractRelations();

        $this->assertInstanceOf(SpyProductLabelProductAbstractQuery::class, $query);
    }
}
