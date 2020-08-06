<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\StorageDatabase\Storage;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Client\StorageDatabase\Dependency\Service\StorageDatabaseToUtilEncodingBridge;
use Spryker\Client\StorageDatabase\Dependency\Service\StorageDatabaseToUtilEncodingInterface;
use Spryker\Client\StorageDatabase\Storage\StorageDatabase;
use Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group StorageDatabase
 * @group Storage
 * @group StorageDatabaseTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Client\StorageDatabase\StorageDatabaseClientTester $tester
 */
class StorageDatabaseTest extends Unit
{
    protected const DUMMY_KEY = 'dummy_key';
    protected const DUMMY_VALUE = 'dummy_value';

    /**
     * @var \Spryker\Client\StorageDatabase\Storage\Reader\AbstractStorageReader|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $storageReaderPluginMock;

    /**
     * @var \Spryker\Client\StorageDatabase\Storage\StorageDatabaseInterface
     */
    protected $storageDatabase;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setupStorageReaderPluginMock();
        $this->setupStorageDatabase();
    }

    /**
     * @return void
     */
    public function testGetReadsFromStorage(): void
    {
        $dummyKey = static::DUMMY_KEY;

        $this->storageReaderPluginMock
            ->expects($this->once())
            ->method('get')
            ->with($dummyKey);

        $this->storageDatabase->get($dummyKey);
    }

    /**
     * @dataProvider getReturnsDecodedResultWhenPresentProvider
     *
     * @param string $storageReaderReturnValue
     * @param mixed $expectedResult
     *
     * @return void
     */
    public function testGetReturnsDecodedResultWhenPresent(string $storageReaderReturnValue, $expectedResult): void
    {
        $this->storageReaderPluginMock
            ->method('get')
            ->willReturn($storageReaderReturnValue);

        $result = $this->storageDatabase->get(static::DUMMY_KEY);
        $this->assertNotEmpty($result);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function getReturnsDecodedResultWhenPresentProvider(): array
    {
        return [
            [sprintf('"%s"', static::DUMMY_VALUE), static::DUMMY_VALUE],
            [sprintf('["%s"]', static::DUMMY_VALUE), [static::DUMMY_VALUE]],
            [sprintf('{"%s": "%s"}', static::DUMMY_KEY, static::DUMMY_VALUE), [static::DUMMY_KEY => static::DUMMY_VALUE]],
        ];
    }

    /**
     * @return void
     */
    public function testGetReturnsNullWhenEmptyResult(): void
    {
        $this->storageReaderPluginMock
            ->method('get')
            ->willReturn('');

        $this->assertNull($this->storageDatabase->get(static::DUMMY_KEY));
    }

    /**
     * @return void
     */
    public function testGetMultiReturnsEmptyArrayWhenNoKeys(): void
    {
        $result = $this->storageDatabase->getMulti([]);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @return void
     */
    public function testGetMultiReturnsResultWithPrefixedKeys(): void
    {
        $this->storageReaderPluginMock
            ->method('getMulti')
            ->willReturn(
                $this->getMultiResult()
            );

        $this->assertEquals($this->getMultiResultWithPrefixedKeys(), $this->storageDatabase->getMulti(['key1', 'key2']));
    }

    /**
     * @return array
     */
    public function testAddsReadAccessStatsForGetWhenDebugEnabled(): array
    {
        // Arrange
        $this->storageDatabase->setDebug(true);

        // Act
        $this->storageDatabase->get(static::DUMMY_KEY);
        $accessStats = $this->storageDatabase->getAccessStats();

        // Assert
        $this->assertEquals(1, $accessStats['count']['read']);
        $this->assertCount(1, $accessStats['keys']['read']);
        $this->assertEquals(static::DUMMY_KEY, $accessStats['keys']['read'][0]);

        return $accessStats;
    }

    /**
     * @depends testAddsReadAccessStatsForGetWhenDebugEnabled
     *
     * @param array $accessStats
     *
     * @return void
     */
    public function testCanResetAccessStats(array $accessStats): void
    {
        $this->setAccessStats($accessStats);
        $this->assertEquals($accessStats, $this->storageDatabase->getAccessStats());

        $this->storageDatabase->resetAccessStats();
        $this->assertEquals($this->getEmptyAccessStats(), $this->storageDatabase->getAccessStats());
    }

    /**
     * @return void
     */
    public function testDoesntAddReadAccessStatsForGetWhenDebugDisabled(): void
    {
        // Arrange
        $this->storageDatabase->setDebug(false);

        // Act
        $this->storageDatabase->get(static::DUMMY_KEY);
        $accessStats = $this->storageDatabase->getAccessStats();

        // Assert
        $this->assertEquals($this->getEmptyAccessStats(), $accessStats);
    }

    /**
     * @return array
     */
    protected function getMultiResultWithPrefixedKeys(): array
    {
        $multiResult = $this->getMultiResult();
        $prefixedKeys = [];

        foreach (array_keys($multiResult) as $key) {
            $prefixedKeys[] = sprintf('kv:%s', $key);
        }

        return array_combine($prefixedKeys, $multiResult);
    }

    /**
     * @return array
     */
    protected function getMultiResult(): array
    {
        return [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
    }

    /**
     * @return void
     */
    protected function setupStorageReaderPluginMock(): void
    {
        $this->storageReaderPluginMock = $this->createMock(StorageReaderPluginInterface::class);
    }

    /**
     * @param array $accessStats
     *
     * @return void
     */
    protected function setAccessStats(array $accessStats): void
    {
        $storageDatabaseReflection = new ReflectionClass(StorageDatabase::class);
        $accessStatsReflection = $storageDatabaseReflection->getProperty('accessStats');
        $accessStatsReflection->setAccessible(true);
        $accessStatsReflection->setValue($this->storageDatabase, $accessStats);
    }

    /**
     * @return array
     */
    protected function getEmptyAccessStats(): array
    {
        return [
            'count' => [
                'read' => 0,
            ],
            'keys' => [
                'read' => [],
            ],
        ];
    }

    /**
     * @return void
     */
    protected function setupStorageDatabase(): void
    {
        $this->storageDatabase = new StorageDatabase(
            $this->createUtilEncodingService(),
            $this->storageReaderPluginMock
        );
    }

    /**
     * @return \Spryker\Client\StorageDatabase\Dependency\Service\StorageDatabaseToUtilEncodingInterface
     */
    protected function createUtilEncodingService(): StorageDatabaseToUtilEncodingInterface
    {
        return new StorageDatabaseToUtilEncodingBridge(
            $this->tester->getLocator()->utilEncoding()->service()
        );
    }
}
