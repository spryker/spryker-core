<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace spryker\spryker\Bundles\Store\tests\SprykerTest\Zed\Store\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Business\StoreFacade;

/**
 * Auto-generated group annotations
 * @group spryker
 * @group spryker
 * @group Bundles
 * @group Store
 * @group tests
 * @group SprykerTest
 * @group Zed
 * @group Store
 * @group Business
 * @group Facade
 * @group StoreFacadeTest
 * Add your own group annotations below this line
 */
class StoreFacadeTest extends Unit
{
    public const DEFAULT_STORE_NAME = 'DE';
    /**
     * @var \SprykerTest\Zed\Store\StoreBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetCurrentStoreShouldReturnCurrentStoreTransfer()
    {
        $storeFacade = $this->createStoreFacade();

        $storeTransfer = $storeFacade->getCurrentStore();

        $this->assertInstanceOf(StoreTransfer::class, $storeTransfer);
        $this->assertNotEmpty($storeTransfer->getName());
        $this->assertNotEmpty($storeTransfer->getIdStore());
    }

    /**
     * @return void
     */
    public function testGetAllStoresShouldReturnAllStore()
    {
        $storeFacade = $this->createStoreFacade();

        $stores = $storeFacade->getAllStores();

        $this->assertInternalType('array', $stores);
    }

    /**
     * @return void
     */
    public function testGetStoreByIdShouldReturnStoreFromPersistence()
    {
        $storeFacade = $this->createStoreFacade();

        $storeTransfer = $storeFacade->getStoreById(1);

        $this->assertInstanceOf(StoreTransfer::class, $storeTransfer);
        $this->assertNotEmpty($storeTransfer->getName());
        $this->assertNotEmpty($storeTransfer->getIdStore());
    }

    /**
     * @return void
     */
    public function testGetStoreByNameShouldReturnStore()
    {
        $storeFacade = $this->createStoreFacade();
        $storeTransfer = $storeFacade->getStoreByName(static::DEFAULT_STORE_NAME);

        $this->assertInstanceOf(StoreTransfer::class, $storeTransfer);

        $this->assertEquals(static::DEFAULT_STORE_NAME, $storeTransfer->getName());
        $this->assertNotEmpty($storeTransfer->getIdStore());
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacade
     */
    protected function createStoreFacade()
    {
        return new StoreFacade();
    }
}
