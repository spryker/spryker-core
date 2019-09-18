<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCustomerPermission\Persistence;

use Codeception\Test\Unit;
use Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermissionQuery;
use Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionPersistenceFactory;
use Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCustomerPermission
 * @group Persistence
 * @group ProductCustomerPermissionQueryContainerTest
 * Add your own group annotations below this line
 */
class ProductCustomerPermissionQueryContainerTest extends Unit
{
    /**
     * @return void
     */
    public function testQueryProductCustomerPermissionByCustomerReturnsCorrectQuery()
    {
        $productCustomerPermissionQueryContainer = new ProductCustomerPermissionQueryContainer();
        $productCustomerPermissionQueryContainer->setFactory(new ProductCustomerPermissionPersistenceFactory());
        $query = $productCustomerPermissionQueryContainer->queryProductCustomerPermissionByCustomer(1);

        $this->assertInstanceOf(SpyProductCustomerPermissionQuery::class, $query);
    }

    /**
     * @return void
     */
    public function testQueryProductCustomerPermissionByCustomerAndProductsReturnsCorrectQuery()
    {
        $productCustomerPermissionQueryContainer = new ProductCustomerPermissionQueryContainer();
        $productCustomerPermissionQueryContainer->setFactory(new ProductCustomerPermissionPersistenceFactory());
        $query = $productCustomerPermissionQueryContainer->queryProductCustomerPermissionByCustomerAndProducts(1, [1, 2, 3]);

        $this->assertInstanceOf(SpyProductCustomerPermissionQuery::class, $query);
    }
}
