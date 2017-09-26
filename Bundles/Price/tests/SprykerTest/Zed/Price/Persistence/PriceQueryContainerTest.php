<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Price\Persistence;

use Codeception\Test\Unit;
use Orm\Zed\Price\Persistence\SpyPriceProductQuery;
use Spryker\Zed\Price\Persistence\PricePersistenceFactory;
use Spryker\Zed\Price\Persistence\PriceQueryContainer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Price
 * @group Persistence
 * @group QueryContainer
 * @group PriceQueryContainerTest
 * Add your own group annotations below this line
 */
class PriceQueryContainerTest extends Unit
{

    /**
     * @return void
     */
    public function testQueryAllPriceProductsReturnsCorrectQuery()
    {
        $priceQueryContainer = new PriceQueryContainer();
        $priceQueryContainer->setFactory(new PricePersistenceFactory());
        $query = $priceQueryContainer->queryAllPriceProducts();

        $this->assertInstanceOf(SpyPriceProductQuery::class, $query);
    }
}
