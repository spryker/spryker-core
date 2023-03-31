<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\StoreStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreStorageTransfer;
use Spryker\Client\StoreStorage\Dependency\Client\StoreStorageToStorageClientInterface;
use Spryker\Client\StoreStorage\StoreStorageDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group StoreStorage
 * @group StoreStorageClientTest
 * Add your own group annotations below this line
 */
class StoreStorageClientTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const ANOTHER_STORE_NAME = 'another_store';

    /**
     * @var string
     */
    protected const CURRENCY = 'EUR';

    /**
     * @var string
     */
    protected const LOCALE = 'de_DE';

    /**
     * @var string
     */
    protected const COUNTRY = 'DE';

    /**
     * @var \SprykerTest\Client\StoreStorage\StoreStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testFindStoreByNameReturnsStoreStorage(): void
    {
        // Arrange
        $storeStorageTransfer = (new StoreStorageTransfer())
            ->setIdStore(1)
            ->setName(static::STORE_NAME)
            ->setAvailableCurrencyIsoCodes([static::CURRENCY])
            ->setAvailableLocaleIsoCodes([static::LOCALE])
            ->setCountries([static::COUNTRY])
            ->setStoresWithSharedPersistence([static::ANOTHER_STORE_NAME]);
        $this->setStorageClientMock($storeStorageTransfer);

        // Act
        $foundStoreStorageTransfer = $this->tester->getLocator()->storeStorage()->client()
            ->findStoreByName(static::STORE_NAME);

        // Assert
        $this->assertInstanceOf(StoreStorageTransfer::class, $foundStoreStorageTransfer);
        $this->assertSame($storeStorageTransfer->getNameOrFail(), $foundStoreStorageTransfer->getNameOrFail());
        $this->assertSame($storeStorageTransfer->getIdStoreOrFail(), $foundStoreStorageTransfer->getIdStoreOrFail());
        $this->assertSame($storeStorageTransfer->getAvailableCurrencyIsoCodes(), $foundStoreStorageTransfer->getAvailableCurrencyIsoCodes());
        $this->assertSame($storeStorageTransfer->getAvailableLocaleIsoCodes(), $foundStoreStorageTransfer->getAvailableLocaleIsoCodes());
        $this->assertSame($storeStorageTransfer->getCountries(), $foundStoreStorageTransfer->getCountries());
        $this->assertSame($storeStorageTransfer->getStoresWithSharedPersistence(), $foundStoreStorageTransfer->getStoresWithSharedPersistence());
    }

    /**
     * @return void
     */
    public function testFindStoreByNameWithInvalidName(): void
    {
        // Arrange
        $this->setStorageClientMock();

        // Act
        $foundStoreStorageTransfer = $this->tester->getLocator()->storeStorage()->client()
            ->findStoreByName(static::ANOTHER_STORE_NAME);

        // Assert
        $this->assertNull($foundStoreStorageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreStorageTransfer|null $storeStorageTransfer
     *
     * @return void
     */
    protected function setStorageClientMock(?StoreStorageTransfer $storeStorageTransfer = null): void
    {
        $returnData = $storeStorageTransfer ? $storeStorageTransfer->toArray() : null;

        $storageClientMock = $this->createMock(StoreStorageToStorageClientInterface::class);
        $storageClientMock->expects($this->once())
            ->method('get')
            ->willReturn($returnData);

        $this->tester->setDependency(StoreStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);
    }
}
