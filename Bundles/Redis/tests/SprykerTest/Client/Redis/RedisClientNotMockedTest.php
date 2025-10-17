<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Redis;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Generated\Shared\Transfer\RedisCredentialsTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Redis
 * @group RedisClientNotMockedTest
 * Add your own group annotations below this line
 */
class RedisClientNotMockedTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_KEY = 'test_redis_key';

    /**
     * @var string
     */
    protected const TEST_VALUE = 'test_redis_value';

    /**
     * @var string
     */
    protected const TEST_LARGE_VALUE = 'test_redis_large_value_for_compression_test_with_more_than_200_bytes_test_redis_large_value_for_compression_test_with_more_than_200_bytes_test_redis_large_value_for_compression_test_with_more_than_200_bytes';

    /**
     * @var string
     */
    protected const CONNECTION_KEY = 'phpredis_test_connection';

    /**
     * @var \SprykerTest\Client\Redis\RedisClientTester
     */
    protected RedisClientTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->resetClientPool();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->cleanupRedis();
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testGet(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);
        $this->tester->getClient()->set(static::CONNECTION_KEY, static::TEST_KEY, static::TEST_VALUE);

        // Act
        $result = $this->tester->getClient()->get(static::CONNECTION_KEY, static::TEST_KEY);

        // Assert
        $this->assertSame(static::TEST_VALUE, $result);
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testGetNonExistentKey(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);

        // Act
        $result = $this->tester->getClient()->get(static::CONNECTION_KEY, 'non_existent_key');

        // Assert
        $this->assertNull($result);
    }

    /**
     * @dataProvider getCompressionDataProvider
     *
     * @param bool $usePhpredis
     * @param bool $isCompressionEnabled
     *
     * @return void
     */
    public function testGetWithCompression(bool $usePhpredis, bool $isCompressionEnabled): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, $isCompressionEnabled);
        $this->tester->getClient()->set(static::CONNECTION_KEY, static::TEST_KEY, static::TEST_LARGE_VALUE);

        // Act
        $result = $this->tester->getClient()->get(static::CONNECTION_KEY, static::TEST_KEY);

        // Assert
        $this->assertSame(static::TEST_LARGE_VALUE, $result);
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testSetex(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);

        // Act
        $result = $this->tester->getClient()->setex(static::CONNECTION_KEY, static::TEST_KEY, 10, static::TEST_VALUE);

        // Assert
        $this->assertTrue($result);
        $this->assertSame(static::TEST_VALUE, $this->tester->getClient()->get(static::CONNECTION_KEY, static::TEST_KEY));
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testSetexExpires(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);

        // Act
        $this->tester->getClient()->setex(static::CONNECTION_KEY, static::TEST_KEY, 1, static::TEST_VALUE);
        sleep(2);
        $result = $this->tester->getClient()->get(static::CONNECTION_KEY, static::TEST_KEY);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testSet(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);

        // Act
        $result = $this->tester->getClient()->set(static::CONNECTION_KEY, static::TEST_KEY, static::TEST_VALUE);

        // Assert
        $this->assertTrue($result);
        $this->assertSame(static::TEST_VALUE, $this->tester->getClient()->get(static::CONNECTION_KEY, static::TEST_KEY));
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testSetWithExpiration(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);

        // Act
        $result = $this->tester->getClient()->set(static::CONNECTION_KEY, static::TEST_KEY, static::TEST_VALUE, 'EX', 1);
        sleep(2);
        $valueAfterExpiration = $this->tester->getClient()->get(static::CONNECTION_KEY, static::TEST_KEY);

        // Assert
        $this->assertTrue($result);
        $this->assertNull($valueAfterExpiration);
    }

    /**
     * @dataProvider getCompressionDataProvider
     *
     * @param bool $usePhpredis
     * @param bool $isCompressionEnabled
     *
     * @return void
     */
    public function testSetWithCompression(bool $usePhpredis, bool $isCompressionEnabled): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, $isCompressionEnabled);

        // Act
        $result = $this->tester->getClient()->set(static::CONNECTION_KEY, static::TEST_KEY, static::TEST_LARGE_VALUE);

        // Assert
        $this->assertTrue($result);
        $this->assertSame(static::TEST_LARGE_VALUE, $this->tester->getClient()->get(static::CONNECTION_KEY, static::TEST_KEY));
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testDel(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);
        $this->tester->getClient()->set(static::CONNECTION_KEY, static::TEST_KEY, static::TEST_VALUE);

        // Act
        $result = $this->tester->getClient()->del(static::CONNECTION_KEY, [static::TEST_KEY]);

        // Assert
        $this->assertSame(1, $result);
        $this->assertNull($this->tester->getClient()->get(static::CONNECTION_KEY, static::TEST_KEY));
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testDelNonExistentKey(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);

        // Act
        $result = $this->tester->getClient()->del(static::CONNECTION_KEY, ['non_existent_key']);

        // Assert
        $this->assertSame(0, $result);
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testEval(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);
        $script = 'return redis.call("SET", KEYS[1], ARGV[1])';
        $numKeys = 1;
        $keysOrArgs = [static::TEST_KEY, static::TEST_VALUE];

        // Act
        $result = $this->tester->getClient()->eval(static::CONNECTION_KEY, $script, $numKeys, ...$keysOrArgs);

        // Assert
        $this->assertTrue($result);
        $this->assertSame(static::TEST_VALUE, $this->tester->getClient()->get(static::CONNECTION_KEY, static::TEST_KEY));
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testEvalWithInvalidScript(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);
        $script = 'invalid lua script';
        $numKeys = 1;
        $keysOrArgs = [static::TEST_KEY, static::TEST_VALUE];

        // Act & Assert
        try {
            $this->tester->getClient()->eval(static::CONNECTION_KEY, $script, $numKeys, $keysOrArgs);
            $this->fail('Expected exception for invalid Lua script was not thrown');
        } catch (Exception $e) {
            $this->assertTrue(true, 'Exception was thrown for invalid Lua script');
        }
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testConnect(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);

        // Act
        $this->tester->getClient()->connect(static::CONNECTION_KEY);

        // Assert
        $this->assertTrue($this->tester->getClient()->isConnected(static::CONNECTION_KEY));
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testDisconnect(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);
        $this->tester->getClient()->connect(static::CONNECTION_KEY);

        // Act
        $this->tester->getClient()->disconnect(static::CONNECTION_KEY);

        // Assert
        $this->assertFalse($this->tester->getClient()->isConnected(static::CONNECTION_KEY));
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testIsConnected(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);

        // Act & Assert
        $this->tester->getClient()->connect(static::CONNECTION_KEY);
        $this->assertTrue($this->tester->getClient()->isConnected(static::CONNECTION_KEY));

        $this->tester->getClient()->disconnect(static::CONNECTION_KEY);
        $this->assertFalse($this->tester->getClient()->isConnected(static::CONNECTION_KEY));
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testMget(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);
        $key1 = static::TEST_KEY . '_1';
        $key2 = static::TEST_KEY . '_2';
        $value1 = static::TEST_VALUE . '_1';
        $value2 = static::TEST_VALUE . '_2';

        $this->tester->getClient()->set(static::CONNECTION_KEY, $key1, $value1);
        $this->tester->getClient()->set(static::CONNECTION_KEY, $key2, $value2);

        // Act
        $result = $this->tester->getClient()->mget(static::CONNECTION_KEY, [$key1, $key2]);

        // Assert
        $this->assertCount(2, $result);
        $this->assertSame($value1, $result[0]);
        $this->assertSame($value2, $result[1]);
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testMgetWithNonExistentKeys(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);
        $key1 = static::TEST_KEY . '_1';
        $nonExistentKey = 'non_existent_key';
        $value1 = static::TEST_VALUE . '_1';

        $this->tester->getClient()->set(static::CONNECTION_KEY, $key1, $value1);

        // Act
        $result = $this->tester->getClient()->mget(static::CONNECTION_KEY, [$key1, $nonExistentKey]);

        // Assert
        $this->assertCount(2, $result);
        $this->assertSame($value1, $result[0]);
        $this->assertNull($result[1]);
    }

    /**
     * @dataProvider getCompressionDataProvider
     *
     * @param bool $usePhpredis
     * @param bool $isCompressionEnabled
     *
     * @return void
     */
    public function testMgetWithCompression(bool $usePhpredis, bool $isCompressionEnabled): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, $isCompressionEnabled);
        $key1 = static::TEST_KEY . '_1';
        $key2 = static::TEST_KEY . '_2';

        $this->tester->getClient()->set(static::CONNECTION_KEY, $key1, static::TEST_LARGE_VALUE . '_1');
        $this->tester->getClient()->set(static::CONNECTION_KEY, $key2, static::TEST_LARGE_VALUE . '_2');

        // Act
        $result = $this->tester->getClient()->mget(static::CONNECTION_KEY, [$key1, $key2]);

        // Assert
        $this->assertCount(2, $result);
        $this->assertSame(static::TEST_LARGE_VALUE . '_1', $result[0]);
        $this->assertSame(static::TEST_LARGE_VALUE . '_2', $result[1]);
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testMset(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);
        $key1 = static::TEST_KEY . '_1';
        $key2 = static::TEST_KEY . '_2';
        $value1 = static::TEST_VALUE . '_1';
        $value2 = static::TEST_VALUE . '_2';
        $dictionary = [$key1 => $value1, $key2 => $value2];

        // Act
        $result = $this->tester->getClient()->mset(static::CONNECTION_KEY, $dictionary);

        // Assert
        $this->assertTrue($result);
        $this->assertSame($value1, $this->tester->getClient()->get(static::CONNECTION_KEY, $key1));
        $this->assertSame($value2, $this->tester->getClient()->get(static::CONNECTION_KEY, $key2));
    }

    /**
     * @dataProvider getCompressionDataProvider
     *
     * @param bool $usePhpredis
     * @param bool $isCompressionEnabled
     *
     * @return void
     */
    public function testMsetWithCompression(bool $usePhpredis, bool $isCompressionEnabled): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, $isCompressionEnabled);
        $key1 = static::TEST_KEY . '_1';
        $key2 = static::TEST_KEY . '_2';
        $value1 = static::TEST_LARGE_VALUE . '_1';
        $value2 = static::TEST_LARGE_VALUE . '_2';
        $dictionary = [$key1 => $value1, $key2 => $value2];

        // Act
        $result = $this->tester->getClient()->mset(static::CONNECTION_KEY, $dictionary);

        // Assert
        $this->assertTrue($result);
        $this->assertSame($value1, $this->tester->getClient()->get(static::CONNECTION_KEY, $key1));
        $this->assertSame($value2, $this->tester->getClient()->get(static::CONNECTION_KEY, $key2));
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testInfo(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);

        // Act
        $result = $this->tester->getClient()->info(static::CONNECTION_KEY);

        // Assert
        $this->assertIsArray($result); // Info is always empty for some reasons, but should be an array
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testInfoWithSection(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);

        // Act
        $result = $this->tester->getClient()->info(static::CONNECTION_KEY, 'server');

        // Assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testKeys(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);
        $keyPrefix = 'test_pattern_';
        $this->tester->getClient()->set(static::CONNECTION_KEY, $keyPrefix . '1', static::TEST_VALUE);
        $this->tester->getClient()->set(static::CONNECTION_KEY, $keyPrefix . '2', static::TEST_VALUE);

        // Act
        $result = $this->tester->getClient()->keys(static::CONNECTION_KEY, $keyPrefix . '*');

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertContains($keyPrefix . '1', $result);
        $this->assertContains($keyPrefix . '2', $result);
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testKeysWithNonMatchingPattern(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);

        // Act
        $result = $this->tester->getClient()->keys(static::CONNECTION_KEY, 'non_matching_pattern_*');

        // Assert
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testScan(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);
        $keyPrefix = 'test_scan_';
        for ($i = 0; $i < 10; $i++) {
            $this->tester->getClient()->set(static::CONNECTION_KEY, $keyPrefix . $i, static::TEST_VALUE);
        }

        // Act
        $cursor = 0;
        $keys = [];

        do {
            [$cursor, $result] = $this->tester->getClient()->scan(
                static::CONNECTION_KEY,
                $cursor,
                ['pattern' => $keyPrefix . '*', 'count' => 5],
            );
            $cursor = (int)$cursor;
            $keys = array_merge($keys, $result);
        } while ($cursor !== 0);

        // Assert
        $this->assertGreaterThanOrEqual(10, count($keys));
        for ($i = 0; $i < 10; $i++) {
            $this->assertContains($keyPrefix . $i, $keys);
        }
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testDbSize(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);
        $this->cleanupRedis();

        // Set a known number of keys
        for ($i = 0; $i < 5; $i++) {
            $this->tester->getClient()->set(static::CONNECTION_KEY, 'dbsize_test_' . $i, static::TEST_VALUE);
        }

        // Act
        $result = $this->tester->getClient()->dbSize(static::CONNECTION_KEY);

        // Assert
        $this->assertGreaterThanOrEqual(5, $result);
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testFlushDb(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);
        $this->tester->getClient()->set(static::CONNECTION_KEY, static::TEST_KEY, static::TEST_VALUE);

        // Act
        $this->tester->getClient()->flushDb(static::CONNECTION_KEY);

        // Assert
        $this->assertNull($this->tester->getClient()->get(static::CONNECTION_KEY, static::TEST_KEY));
        $this->assertEquals(0, $this->tester->getClient()->dbSize(static::CONNECTION_KEY));
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testIncr(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);
        $counterKey = 'counter_test';
        $this->tester->getClient()->set(static::CONNECTION_KEY, $counterKey, '10');

        // Act
        $result = $this->tester->getClient()->incr(static::CONNECTION_KEY, $counterKey);

        // Assert
        $this->assertEquals(11, $result);
        $this->assertEquals('11', $this->tester->getClient()->get(static::CONNECTION_KEY, $counterKey));
    }

    /**
     * @dataProvider getUsePhpredisDataProvider
     *
     * @param bool $usePhpredis
     *
     * @return void
     */
    public function testIncrWithNonExistentKey(bool $usePhpredis): void
    {
        // Arrange
        $this->mockConfig($usePhpredis, false);
        $counterKey = 'non_existent_counter';

        // Act
        $result = $this->tester->getClient()->incr(static::CONNECTION_KEY, $counterKey);

        // Assert
        $this->assertEquals(1, $result);
        $this->assertEquals('1', $this->tester->getClient()->get(static::CONNECTION_KEY, $counterKey));
    }

    /**
     * @return array
     */
    public function getUsePhpredisDataProvider(): array
    {
        return [
            'usePhpredis=true' => [true],
            'usePhpredis=false' => [false],
        ];
    }

    /**
     * @return array
     */
    public function getCompressionDataProvider(): array
    {
        return [
            'usePhpredis=true, compression=true' => [true, true],
            'usePhpredis=true, compression=false' => [true, false],
            'usePhpredis=false, compression=true' => [false, true],
            'usePhpredis=false, compression=false' => [false, false],
        ];
    }

    /**
     * @param bool $usePhpredis
     * @param bool $isCompressionEnabled
     *
     * @return void
     */
    protected function mockConfig(bool $usePhpredis, bool $isCompressionEnabled): void
    {
        $this->tester->mockConfigMethod('usePhpredis', $usePhpredis);
        $this->tester->mockConfigMethod('isCompressionEnabled', $isCompressionEnabled);
        $this->tester->mockConfigMethod('getMinBytesForCompression', 200);

        $this->tester->mockFactoryMethod('getConfig', $this->tester->getModuleConfig());

        $this->setupTestConnection();
    }

    /**
     * @return void
     */
    protected function setupTestConnection(): void
    {
        $configurationTransfer = (new RedisConfigurationTransfer())
            ->setDataSourceNames([])
            ->setConnectionCredentials(
                $this->getConnectionCredentials(),
            )
            ->setClientOptions(
                json_decode(getenv('SPRYKER_KEY_VALUE_STORE_CONNECTION_OPTIONS') ?: '[]', true) ?: [],
            );

        $this->tester->getClient()->setupConnection(static::CONNECTION_KEY, $configurationTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\RedisCredentialsTransfer
     */
    protected function getConnectionCredentials(): RedisCredentialsTransfer
    {
        return (new RedisCredentialsTransfer())
            ->setScheme('tcp')
            ->setHost('key_value_store') // StorageRedisConstants::STORAGE_REDIS_HOST
            ->setPort(6379) // StorageRedisConstants::STORAGE_REDIS_PORT
            ->setDatabase(5) // unique number different from StorageRedisConstants::STORAGE_REDIS_DATABASE, because it  will be flushed during tests
            ->setPassword(false) // StorageRedisConstants::STORAGE_REDIS_PASSWORD
            ->setIsPersistent(true); // StorageRedisConstants::STORAGE_REDIS_PERSISTENT_CONNECTION
    }

    /**
     * @return void
     */
    protected function cleanupRedis(): void
    {
        $this->tester->getClient()->flushDb(static::CONNECTION_KEY);
    }
}
