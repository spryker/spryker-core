<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Store\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Business\StoreFacade;

/**
 * Auto-generated group annotations
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

        $this->assertIsArray($stores);
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
     * @return void
     */
    public function testValidateQuoteStoreWithMissingStore(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();
        $quoteTransfer = new QuoteTransfer();

        //Act
        $quoteValidationTransfer = $storeFacade->validateQuoteStore($quoteTransfer);

        //Assert
        $this->assertFalse($quoteValidationTransfer->getIsSuccessful());
        $this->assertEquals(1, $quoteValidationTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testValidateQuoteStoreWithMissingStoreName(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();
        $quoteTransfer = (new QuoteTransfer())->setStore(new StoreTransfer());

        //Act
        $quoteValidationTransfer = $storeFacade->validateQuoteStore($quoteTransfer);

        //Assert
        $this->assertFalse($quoteValidationTransfer->getIsSuccessful());
        $this->assertEquals(1, $quoteValidationTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testValidateQuoteStoreWithWrongStoreName(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();
        $quoteTransfer = (new QuoteTransfer())->setStore((new StoreTransfer())->setName('WrongStore'));

        //Act
        $quoteValidationTransfer = $storeFacade->validateQuoteStore($quoteTransfer);

        //Assert
        $this->assertFalse($quoteValidationTransfer->getIsSuccessful());
        $this->assertEquals(1, $quoteValidationTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testValidateQuoteStoreWithCorrectStoreName(): void
    {
        //Arrange
        $storeFacade = $this->createStoreFacade();
        $quoteTransfer = (new QuoteTransfer())->setStore((new StoreTransfer())->setName(static::DEFAULT_STORE_NAME));

        //Act
        $quoteValidationTransfer = $storeFacade->validateQuoteStore($quoteTransfer);

        //Assert
        $this->assertTrue($quoteValidationTransfer->getIsSuccessful());
        $this->assertEquals(0, $quoteValidationTransfer->getErrors()->count());
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacade
     */
    protected function createStoreFacade()
    {
        return new StoreFacade();
    }
}
