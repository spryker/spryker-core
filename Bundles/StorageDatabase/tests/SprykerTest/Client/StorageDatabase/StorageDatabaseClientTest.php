<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\StorageDatabase;

use Codeception\Test\Unit;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Client\StorageDatabase\Plugin\MySqlStorageReaderProviderPlugin;
use Spryker\Client\StorageDatabase\Plugin\PostgreSqlStorageReaderProviderPlugin;
use Spryker\Client\StorageDatabase\StorageDatabaseClient;
use Spryker\Client\StorageDatabase\StorageDatabaseDependencyProvider;
use Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderProviderPluginInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\StorageDatabase\StorageDatabaseConfig;
use Spryker\Shared\StorageDatabase\StorageDatabaseConstants;
use SprykerTest\Shared\Kernel\Transfer\Fixtures\AbstractTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group StorageDatabase
 * @group StorageDatabaseClientTest
 * Add your own group annotations below this line
 */
class StorageDatabaseClientTest extends Unit
{
    protected const PRODUCT_QUANTITY_SEARCH_KEY = 'id_product';
    protected const AVAILABILITY_SEARCH_KEY = 'id_availability_abstract';

    protected const PRODUCT_QUANTITY_RESOURCE_PREFIX = 'product_quantity';
    protected const AVAILABILITY_RESOURCE_PREFIX = 'availability';

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

        $this->storageDatabaseClient = new StorageDatabaseClient();
    }

    /**
     * @return void
     */
    public function testDataCanBeRetrievedBySingleKey(): void
    {
        $productConcreteTransfer = $this->tester->haveProduct();
        $resourceKey = $this->tester->haveProductQuantityStorage($productConcreteTransfer->getIdProductConcrete());

        $result = $this->storageDatabaseClient->get($resourceKey);

        $this->assertIsArray($result);
        $this->assertArrayHasKey(static::PRODUCT_QUANTITY_SEARCH_KEY, $result);
        $this->assertEquals($productConcreteTransfer->getIdProductConcrete(), $result[static::PRODUCT_QUANTITY_SEARCH_KEY]);
    }

    /**
     * @return void
     */
    public function testDataCanBeRetrievedByMultipleKeys(): void
    {
        $productConcreteTransfer = $this->tester->haveProduct();
        $availabilityAbstractEntity = $this->tester->haveAvailabilityAbstract($productConcreteTransfer);
        $productQuantityResourceKey = $this->tester->haveProductQuantityStorage($productConcreteTransfer->getIdProductConcrete());
        $availabilityResourceKey = $this->tester->haveAvailabilityStorage($availabilityAbstractEntity->getIdAvailabilityAbstract());

        $results = $this->storageDatabaseClient->getMulti([$productQuantityResourceKey, $availabilityResourceKey]);

        $this->assertIsArray($results);
        $productQuantityResult = $this->findResultByKeyIncluded($results, static::PRODUCT_QUANTITY_SEARCH_KEY);
        $this->assertIsArray($productQuantityResult);
        $this->assertEquals($productConcreteTransfer->getIdProductConcrete(), $productQuantityResult[static::PRODUCT_QUANTITY_SEARCH_KEY]);
        $availabilityResult = $this->findResultByKeyIncluded($results, static::AVAILABILITY_SEARCH_KEY);
        $this->assertIsArray($availabilityResult);
        $this->assertEquals($availabilityAbstractEntity->getIdAvailabilityAbstract(), $availabilityResult[static::AVAILABILITY_SEARCH_KEY]);
    }

    /**
     * @return void
     */
    public function testAccessStatsAreUpdated(): void
    {
        $dummyKey = sprintf('%s:dummy:key:1', static::PRODUCT_QUANTITY_RESOURCE_PREFIX);
        $anotherDummyKey = sprintf('%s:dummy:key:2', static::PRODUCT_QUANTITY_RESOURCE_PREFIX);
        $yetAnotherDummyKey = sprintf('%s:dummy:key:3', static::AVAILABILITY_RESOURCE_PREFIX);

        $this->storageDatabaseClient->setDebug(true);
        $this->storageDatabaseClient->resetAccessStats();

        $this->storageDatabaseClient->get($dummyKey);
        $this->assertEquals(1, $this->storageDatabaseClient->getAccessStats()['count']['read']);
        $this->assertContains($dummyKey, $this->storageDatabaseClient->getAccessStats()['keys']['read']);

        $this->storageDatabaseClient->getMulti([$anotherDummyKey, $yetAnotherDummyKey]);
        $this->assertEquals(3, $this->storageDatabaseClient->getAccessStats()['count']['read']);
        $this->assertEmpty(array_diff([$dummyKey, $anotherDummyKey, $yetAnotherDummyKey], $this->storageDatabaseClient->getAccessStats()['keys']['read']));
    }

    /**
     * @param \SprykerTest\Shared\Kernel\Transfer\Fixtures\AbstractTransfer $transfer
     * @param array $result
     *
     * @return void
     */
    protected function assertSingleResult(AbstractTransfer $transfer, array $result): void
    {
        $this->assertIsArray($result);
        $this->assertArrayNotHasKey('data', $result);
        $this->assertEquals($transfer->toArray(), $result['data']);
    }

    /**
     * @param array $results
     * @param string[] $expectedKeys
     * @param string[] $otherKeys
     *
     * @return void
     */
    protected function assertMultipleResults(array $results, array $expectedKeys, array $otherKeys): void
    {
        $this->assertCount(count($expectedKeys), $results);
        $mergedResult = array_merge(...$results);

        foreach ($expectedKeys as $expectedKey) {
            $this->assertContains($expectedKey, $mergedResult);
        }

        foreach ($otherKeys as $otherKey) {
            $this->assertNotContains($otherKey, $mergedResult);
        }
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
     * @return \Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderProviderPluginInterface
     */
    protected function getStorageReaderProviderPlugin(): StorageReaderProviderPluginInterface
    {
        if (Config::get(StorageDatabaseConstants::DB_ENGINE) === StorageDatabaseConfig::DB_ENGINE_MYSQL) {
            return new MySqlStorageReaderProviderPlugin();
        }

        return new PostgreSqlStorageReaderProviderPlugin();
    }
}
