<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage\Helper;

use Codeception\Test\Unit;
use PHPUnit\Framework\ExpectationFailedException;
use Ramsey\Uuid\Uuid;
use Spryker\Client\Storage\StorageClientInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Storage
 * @group Helper
 * @group StorageHelperTest
 * Add your own group annotations below this line
 */
class StorageHelperTest extends Unit
{
    use StorageHelperTrait;
    use LocatorHelperTrait;

    /**
     * @var string
     */
    protected const STORAGE_VALUE = 'value';

    /**
     * @return void
     */
    public function testGetClientReturnsTheMockedStorageClient(): void
    {
        $this->assertInstanceOf(StorageClientInterface::class, $this->getStorageHelper()->getStorageClient());
    }

    /**
     * @return void
     */
    public function testGetTheStorageClientWithTheLocatorReturnsTheMockedStorageClient(): void
    {
        // Arrange - Add some data to the in-memory storage
        $storageKey = $this->getStorageKey();
        $this->getStorageHelper()->getStorageClient()->set($storageKey, static::STORAGE_VALUE);

        // Act - Get the StorageClient from the locator
        $client = $this->getLocatorHelper()->getLocator()->storage()->client();

        // Assert - When both values are the same we know that data set in the in-memory storage can be retrieved from anywhere by using the locator.
        $this->assertSame(static::STORAGE_VALUE, $client->get($storageKey));
    }

    /**
     * @return void
     */
    public function testAssertStorageHasKeyThrowsExceptionWhenStorageDoesNotContainKey(): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->getStorageHelper()->assertStorageHasKey('not existing key');
    }

    /**
     * @return void
     */
    public function testAssertStorageHasKeyWithKeyInStorage(): void
    {
        $storageKey = $this->getStorageKey();

        $this->getStorageHelper()->getStorageClient()->set($storageKey, static::STORAGE_VALUE);
        $this->getStorageHelper()->assertStorageHasKey($storageKey);
    }

    /**
     * @return string
     */
    protected function getStorageKey(): string
    {
        return Uuid::uuid4()->toString();
    }
}
