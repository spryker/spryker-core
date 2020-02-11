<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCustomerPermission\Business;

use Codeception\Test\Unit;
use Orm\Zed\ProductCustomerPermission\Persistence\Map\SpyProductCustomerPermissionTableMap;
use Spryker\Zed\ProductCustomerPermission\Business\ProductCustomerPermissionFacadeInterface;
use Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainer;
use Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCustomerPermission
 * @group Business
 * @group Facade
 * @group ProductCustomerPermissionFacadeTest
 * Add your own group annotations below this line
 */
class ProductCustomerPermissionFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductCustomerPermission\ProductCustomerPermissionBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSaveCustomerProductPermissionAddsProductPermissionToCustomer(): void
    {
        $product = $this->tester->haveProductAbstract();
        $customer = $this->tester->haveCustomer();

        $this->getProductCustomerPermissionFacade()
            ->saveCustomerProductPermission($customer->getIdCustomer(), $product->getIdProductAbstract());

        $this->assertNotNull(
            $this->getQueryContainer()
            ->queryProductCustomerPermissionByCustomerAndProducts($customer->getIdCustomer(), [$product->getIdProductAbstract()])
            ->findOne()
        );
    }

    /**
     * @return void
     */
    public function testDeleteCustomerProductPermissionRemoveProductPermissionFromCustomer(): void
    {
        $product = $this->tester->haveProductAbstract();
        $customer = $this->tester->haveCustomer();

        $this->getProductCustomerPermissionFacade()
            ->saveCustomerProductPermission($customer->getIdCustomer(), $product->getIdProductAbstract());

        $this->getProductCustomerPermissionFacade()
            ->deleteCustomerProductPermission($customer->getIdCustomer(), $product->getIdProductAbstract());

        $this->assertNull(
            $this->getQueryContainer()
                ->queryProductCustomerPermissionByCustomerAndProducts(
                    $customer->getIdCustomer(),
                    [$product->getIdProductAbstract()]
                )
                ->findOne()
        );
    }

    /**
     * @return void
     */
    public function testSaveCustomerProductPermissionsAddsSpecifiedProductPermissionsToCustomer(): void
    {
        $customer = $this->tester->haveCustomer();

        $idProductAbstracts = [];
        for ($i = 1; $i <= 10; $i++) {
            $idProductAbstracts[] = $this->tester->haveProductAbstract()->getIdProductAbstract();
        }

        $this->getProductCustomerPermissionFacade()
            ->saveCustomerProductPermissions($customer->getIdCustomer(), $idProductAbstracts);

        $resultIdProductAbstracts = $this->getQueryContainer()
            ->queryProductCustomerPermissionByCustomer($customer->getIdCustomer())
            ->select(SpyProductCustomerPermissionTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->find()
            ->toArray();

        $this->assertEquals($idProductAbstracts, $resultIdProductAbstracts);
    }

    /**
     * @return void
     */
    public function testDeleteCustomerProductPermissionsRemoveSpecifiedProductPermissionsFromCustomer(): void
    {
        $customer = $this->tester->haveCustomer();

        $idProductAbstracts = [];
        for ($i = 1; $i <= 10; $i++) {
            $idProductAbstracts[] = $this->tester->haveProductAbstract()->getIdProductAbstract();
        }

        $this->getProductCustomerPermissionFacade()
            ->saveCustomerProductPermissions($customer->getIdCustomer(), $idProductAbstracts);

        $this->getProductCustomerPermissionFacade()
            ->deleteCustomerProductPermissions($customer->getIdCustomer(), $idProductAbstracts);

        $this->assertNull(
            $this->getQueryContainer()
                ->queryProductCustomerPermissionByCustomer($customer->getIdCustomer())
                ->findOne()
        );
    }

    /**
     * @return void
     */
    public function testDeleteAllCustomerProductPermissionsRemoveAllProductPermissionsFromCustomer(): void
    {
        $customer = $this->tester->haveCustomer();

        $idProductAbstracts = [];
        for ($i = 1; $i <= 10; $i++) {
            $idProductAbstracts[] = $this->tester->haveProductAbstract()->getIdProductAbstract();
        }

        $this->getProductCustomerPermissionFacade()
            ->saveCustomerProductPermissions($customer->getIdCustomer(), $idProductAbstracts);

        $this->getProductCustomerPermissionFacade()
            ->deleteAllCustomerProductPermissions($customer->getIdCustomer());

        $this->assertNull(
            $this->getQueryContainer()
                ->queryProductCustomerPermissionByCustomer($customer->getIdCustomer())
                ->findOne()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCustomerPermission\Business\ProductCustomerPermissionFacadeInterface
     */
    protected function getProductCustomerPermissionFacade(): ProductCustomerPermissionFacadeInterface
    {
        return $this->tester->getFacade();
    }

    /**
     * @return \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface
     */
    protected function getQueryContainer(): ProductCustomerPermissionQueryContainerInterface
    {
        return new ProductCustomerPermissionQueryContainer();
    }
}
