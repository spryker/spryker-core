<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\StorageDatabase;

use Codeception\Test\Unit;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Client\StorageDatabase\Plugin\MySqlStorageReaderPlugin;
use Spryker\Client\StorageDatabase\Plugin\PostgreSqlStorageReaderPlugin;
use Spryker\Client\StorageDatabase\StorageDatabaseClient;
use Spryker\Client\StorageDatabase\StorageDatabaseDependencyProvider;
use Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderPluginInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\StorageDatabase\StorageDatabaseConfig;
use Spryker\Shared\StorageDatabase\StorageDatabaseConstants;
use SprykerTest\Client\StorageDatabase\Helper\StorageDatabaseHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group StorageDatabase
 * @group StorageDatabaseClientTest
 * Add your own group annotations below this line
 */
class StorageDatabaseClientTest extends Unit
{
    protected const KEY_PREFIX = 'kv:';

    protected const FIRST_FIXTURE_ROW = 'row1';
    protected const SECOND_FIXTURE_ROW = 'row2';
    protected const THIRD_FIXTURE_ROW = 'row3';

    protected const FIRST_DUMMY_KEY = 'test_fixture:1';
    protected const SECOND_DUMMY_KEY = 'test_fixture:2';
    protected const THIRD_DUMMY_KEY = 'test_fixture:3';

    protected const FIRST_DUMMY_ALIAS_KEY = 'test_fixture:alias:1';
    protected const SECOND_DUMMY_ALIAS_KEY = 'test_fixture:alias:2';
    protected const THIRD_DUMMY_ALIAS_KEY = 'test_fixture:alias:3';

    /**
     * @var bool
     */
    protected static $isFixtureTableInitialized = false;

    /**
     * @var array
     */
    protected $fixtureDataSet = [
        self::FIRST_FIXTURE_ROW => [
            StorageDatabaseHelper::COLUMN_KEY => self::FIRST_DUMMY_KEY,
            StorageDatabaseHelper::COLUMN_DATA => ['foo' => 'bar'],
            StorageDatabaseHelper::COLUMN_ALIAS_KEYS => [self::FIRST_DUMMY_ALIAS_KEY => ['id' => 1]],
        ],
        self::SECOND_FIXTURE_ROW => [
            StorageDatabaseHelper::COLUMN_KEY => self::SECOND_DUMMY_KEY,
            StorageDatabaseHelper::COLUMN_DATA => ['bas' => 'foobar'],
            StorageDatabaseHelper::COLUMN_ALIAS_KEYS => [
                self::SECOND_DUMMY_ALIAS_KEY => ['id' => 2],
                'another alias key' => ['id' => 3],
            ],
        ],
        self::THIRD_FIXTURE_ROW => [
            StorageDatabaseHelper::COLUMN_KEY => self::THIRD_DUMMY_KEY,
            StorageDatabaseHelper::COLUMN_DATA => ['fixture string'],
            StorageDatabaseHelper::COLUMN_ALIAS_KEYS => [
                self::THIRD_DUMMY_ALIAS_KEY => ['id' => 4],
            ],
        ],
    ];

    /**
     * @var \SprykerTest\Client\StorageDatabase\StorageDatabaseClientTester
     */
    protected $tester;

    /**
     * @var \Spryker\Client\StorageDatabase\StorageDatabaseClientInterface
     */
    protected $storageDatabaseClient;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->setupStorageReaderPlugins();

        $this->setUpDependencies();
        $this->populateFixtureTable();

        $this->storageDatabaseClient = new StorageDatabaseClient();
    }

    /**
     * @return void
     */
    public function testDataCanBeRetrievedBySingleKey(): void
    {
        // Arrange
        $rowId = static::FIRST_FIXTURE_ROW;
        $key = $this->fixtureDataSet[$rowId][StorageDatabaseHelper::COLUMN_KEY];

        // Act
        $result = $this->storageDatabaseClient->get($key);

        // Assert
        $this->assertSingleKeyResult($rowId, $result);
    }

    /**
     * @return void
     */
    public function testDataCanBeRetrievedByMultipleKeys(): void
    {
        // Arrange
        $rowId = static::FIRST_FIXTURE_ROW;
        $anotherRowId = static::SECOND_FIXTURE_ROW;
        $keys = [
            $this->fixtureDataSet[$rowId][StorageDatabaseHelper::COLUMN_KEY],
            $this->fixtureDataSet[$anotherRowId][StorageDatabaseHelper::COLUMN_KEY],
        ];

        // Act
        $result = $this->storageDatabaseClient->getMulti($keys);

        // Assert
        $this->assertMultipleKeyResults([$rowId, $anotherRowId], $result);
    }

    /**
     * @return void
     */
    public function testDataCanBeRetrievedBySingleAliasKey(): void
    {
        // Arrange
        $rowKey = static::THIRD_FIXTURE_ROW;
        $aliasKey = static::THIRD_DUMMY_ALIAS_KEY;

        // Act
        $result = $this->storageDatabaseClient->get($aliasKey);

        // Assert
        $this->assertSingleAliasKeyResult($rowKey, $aliasKey, $result);
    }

    /**
     * @return void
     */
    public function testDataCanBeRetrievedByMultipleAliasKeys(): void
    {
        // Arrange
        $firstRowKey = static::FIRST_DUMMY_KEY;
        $secondRowKey = static::SECOND_DUMMY_KEY;
        $aliasKeys = [
            static::FIRST_DUMMY_ALIAS_KEY,
            static::SECOND_DUMMY_ALIAS_KEY,
        ];

        // Act
        $result = $this->storageDatabaseClient->getMulti($aliasKeys);

        // Assert
        $this->assertMultipleAliasKeyResults($aliasKeys, $result);
    }

    /**
     * @return void
     */
    public function testAccessStatsAreUpdated(): void
    {
        $this->storageDatabaseClient->setDebug(true);
        $this->storageDatabaseClient->resetAccessStats();

        $this->storageDatabaseClient->get(static::FIRST_DUMMY_KEY);
        $this->assertSame(1, $this->storageDatabaseClient->getAccessStats()['count']['read']);
        $this->assertContains(static::FIRST_DUMMY_KEY, $this->storageDatabaseClient->getAccessStats()['keys']['read']);

        $this->storageDatabaseClient->getMulti([static::SECOND_DUMMY_KEY, static::THIRD_DUMMY_KEY]);
        $this->assertSame(3, $this->storageDatabaseClient->getAccessStats()['count']['read']);
        $this->assertEmpty(array_diff([static::FIRST_DUMMY_KEY, static::SECOND_DUMMY_KEY, static::THIRD_DUMMY_KEY], $this->storageDatabaseClient->getAccessStats()['keys']['read']));
    }

    /**
     * @param string $rowId
     * @param array $result
     *
     * @return void
     */
    protected function assertSingleKeyResult(string $rowId, array $result): void
    {
        $this->assertEquals($this->fixtureDataSet[$rowId][StorageDatabaseHelper::COLUMN_DATA], $result);
    }

    /**
     * @param array $rowIds
     * @param array $result
     *
     * @return void
     */
    protected function assertMultipleKeyResults(array $rowIds, array $result): void
    {
        $this->assertSame(count($rowIds), count($result), 'Number of data sets returned does not match the number of keys, used for search.');

        foreach ($rowIds as $rowId) {
            $expectedKey = $this->getPrefixedKey($this->fixtureDataSet[$rowId][StorageDatabaseHelper::COLUMN_KEY]);
            $expectedResult = $this->fixtureDataSet[$rowId][StorageDatabaseHelper::COLUMN_DATA];
            $this->assertArrayHasKey($expectedKey, $result);
            $this->assertEquals($expectedResult, $this->tester->decodeJson($result[$expectedKey]));
        }
    }

    /**
     * @param string $rowId
     * @param string $aliasKey
     * @param array $result
     *
     * @return void
     */
    protected function assertSingleAliasKeyResult(string $rowId, string $aliasKey, array $result): void
    {
        $fixtureAliasKeysData = $this->fixtureDataSet[$rowId][StorageDatabaseHelper::COLUMN_ALIAS_KEYS];
        $this->assertEquals($fixtureAliasKeysData[$aliasKey], $result);
    }

    /**
     * @param array $aliasKeys
     * @param array $result
     *
     * @return void
     */
    protected function assertMultipleAliasKeyResults(array $aliasKeys, array $result): void
    {
        $this->assertSame(count($aliasKeys), count($result));

        foreach ($aliasKeys as $aliasKey) {
            foreach ($this->fixtureDataSet as $fixtureRowData) {
                if (!array_key_exists($aliasKey, $fixtureRowData[StorageDatabaseHelper::COLUMN_ALIAS_KEYS])) {
                    continue;
                }

                $expectedAliasKey = $this->getPrefixedKey($aliasKey);
                $expectedResult = $fixtureRowData[StorageDatabaseHelper::COLUMN_ALIAS_KEYS][$aliasKey];
                $this->assertArrayHasKey($expectedAliasKey, $result);
                $this->assertEquals($expectedResult, $this->tester->decodeJson($result[$expectedAliasKey]));
            }
        }
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getPrefixedKey(string $key): string
    {
        return static::KEY_PREFIX . $key;
    }

    /**
     * @param array $results
     * @param string $searchKey
     *
     * @return array|null
     */
    protected function findResultByKeyIncluded(array $results, string $searchKey): ?array
    {
        foreach ($results as $result) {
            $result = json_decode($result, true);

            if (is_array($result) && array_key_exists($searchKey, $result)) {
                return $result;
            }
        }

        return null;
    }

    /**
     * @return void
     */
    protected function setUpDependencies(): void
    {
        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->tester->setDependency(StorageDatabaseDependencyProvider::PLUGIN_STORAGE_READER_PROVIDER, function () {
            return $this->getStorageReaderProviderPlugin();
        });
    }

    /**
     * @return \Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderPluginInterface
     */
    protected function getStorageReaderProviderPlugin(): StorageReaderPluginInterface
    {
        if (Config::get(StorageDatabaseConstants::DB_ENGINE) === StorageDatabaseConfig::DB_ENGINE_MYSQL) {
            return new MySqlStorageReaderPlugin();
        }

        return new PostgreSqlStorageReaderPlugin();
    }

    /**
     * @return void
     */
    protected function populateFixtureTable(): void
    {
        if (static::$isFixtureTableInitialized) {
            return;
        }

        foreach ($this->fixtureDataSet as $fixtureRowData) {
            $this->tester->haveRecordInFixtureStorageTable(
                $fixtureRowData[StorageDatabaseHelper::COLUMN_KEY],
                $fixtureRowData[StorageDatabaseHelper::COLUMN_DATA],
                $fixtureRowData[StorageDatabaseHelper::COLUMN_ALIAS_KEYS]
            );
        }

        static::$isFixtureTableInitialized = true;
    }
}
