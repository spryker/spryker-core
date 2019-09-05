<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\StorageRedis;

use Codeception\Test\Unit;
use Spryker\Client\StorageRedis\StorageRedisClient;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group StorageRedis
 * @group StorageRedisClientTest
 * Add your own group annotations below this line
 */
class StorageRedisClientTest extends Unit
{
    protected const DUMMY_KEY = 'dummy-key';
    protected const ANOTHER_DUMMY_KEY = 'another-dummy-key';

    protected const DUMMY_VALUE = 'dummy-value';
    protected const ANOTHER_DUMMY_VALUE = 'another-dummy-value';

    protected const KEY_PREFIX = 'kv';

    /**
     * @var array
     */
    protected $dummyMultiData = [
        self::DUMMY_KEY => self::DUMMY_VALUE,
        self::ANOTHER_DUMMY_KEY => self::ANOTHER_DUMMY_VALUE,
    ];

    /**
     * @var \Spryker\Client\StorageRedis\StorageRedisClientInterface
     */
    protected $storageRedisClient;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->storageRedisClient = new StorageRedisClient();
        $this->cleanupStorage();
    }

    /**
     * @return void
     */
    public function testCanSetAndGetValueForKey(): void
    {
        $this->storageRedisClient->set(static::DUMMY_KEY, static::DUMMY_VALUE);

        $this->assertEquals(static::DUMMY_VALUE, $this->storageRedisClient->get(static::DUMMY_KEY));
    }

    /**
     * @return void
     */
    public function testCanSetValuesForMultipleKeys(): void
    {
        $this->storageRedisClient->setMulti($this->dummyMultiData);
        $this->assertEquals(static::DUMMY_VALUE, $this->storageRedisClient->get(static::DUMMY_KEY));
        $this->assertEquals(static::ANOTHER_DUMMY_VALUE, $this->storageRedisClient->get(static::ANOTHER_DUMMY_KEY));
    }

    /**
     * @return void
     */
    public function testCanDeleteKey(): void
    {
        $this->storageRedisClient->set(static::DUMMY_KEY, static::DUMMY_VALUE);
        $this->storageRedisClient->delete(static::DUMMY_KEY);
        $this->assertEmpty(
            $this->storageRedisClient->get(static::DUMMY_KEY)
        );
    }

    /**
     * @return void
     */
    public function testCanDeleteMultipleKeys(): void
    {
        $this->storageRedisClient->setMulti($this->dummyMultiData);
        $this->storageRedisClient->delete(static::DUMMY_KEY);
        $this->storageRedisClient->delete(static::ANOTHER_DUMMY_KEY);

        $this->assertEmpty(
            $this->storageRedisClient->get(static::DUMMY_KEY)
        );
        $this->assertEmpty(
            $this->storageRedisClient->get(static::ANOTHER_DUMMY_KEY)
        );
    }

    /**
     * @return void
     */
    public function testCanGetValuesForMultipleKeys(): void
    {
        $this->storageRedisClient->setMulti($this->dummyMultiData);
        $result = $this->storageRedisClient->getMulti(array_keys($this->dummyMultiData));

        $this->assertIsArray($result);
        $this->assertEquals(
            array_values($result),
            array_values($this->dummyMultiData)
        );
    }

    /**
     * @return void
     */
    public function testCanGetAllKeys(): void
    {
        $result = $this->storageRedisClient->getAllKeys();

        $this->assertIsArray($result);
    }

    /**
     * @return void
     */
    public function testCanGetKeysByPattern(): void
    {
        $this->storageRedisClient->setMulti($this->dummyMultiData);
        $result = $this->storageRedisClient->getKeys('*dummy-key*');

        $this->assertIsArray($result);
        $this->assertEmpty(
            array_diff($this->prefixKeys(array_keys($this->dummyMultiData)), array_values($result))
        );
    }

    /**
     * @return void
     */
    public function testCanSetValueWithTtl(): void
    {
        $this->storageRedisClient->set(static::DUMMY_KEY, static::DUMMY_VALUE, 1);

        $this->assertEquals(static::DUMMY_VALUE, $this->storageRedisClient->get(static::DUMMY_KEY));

        sleep(2);

        $this->assertEmpty($this->storageRedisClient->get(static::DUMMY_KEY));
    }

    /**
     * @param string[] $keys
     *
     * @return string[]
     */
    protected function prefixKeys(array $keys): array
    {
        $prefixedKeys = [];

        foreach ($keys as $key) {
            $prefixedKeys[] = sprintf('%s:%s', static::KEY_PREFIX, $key);
        }

        return $prefixedKeys;
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->cleanupStorage();
    }

    /**
     * @return void
     */
    protected function cleanupStorage(): void
    {
        $this->storageRedisClient->deleteMulti(array_keys($this->dummyMultiData));
    }
}
