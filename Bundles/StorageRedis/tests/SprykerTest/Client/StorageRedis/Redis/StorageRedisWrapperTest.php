<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\StorageRedis\Redis;

use Codeception\Test\Unit;
use Spryker\Client\StorageRedis\Dependency\Client\StorageRedisToRedisClientInterface;
use Spryker\Client\StorageRedis\Redis\StorageRedisWrapper;
use Spryker\Client\StorageRedis\StorageRedisConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group StorageRedis
 * @group Redis
 * @group StorageRedisWrapperTest
 * Add your own group annotations below this line
 */
class StorageRedisWrapperTest extends Unit
{
    protected const CONNECTION_KEY = 'connection key';
    protected const KEY_PREFIX = 'kv';

    protected const PLAIN_TEXT_DATA = 'plain text data';
    protected const JSON_DATA = '{"data": "dummy data"}';

    protected const PLAIN_TEXT_KEY = 'plainTextKey';
    protected const JSON_KEY = 'jsonKey';

    /**
     * @var array
     */
    protected $dummyStorage = [];

    /**
     * @var \Spryker\Client\StorageRedis\Redis\StorageRedisWrapperInterface
     */
    protected $storageRedisWrapper;

    /**
     * @var \Spryker\Client\StorageRedis\Dependency\Client\StorageRedisToRedisClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $redisClientMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setupDummyStorage();
        $this->redisClientMock = $this->createMock(StorageRedisToRedisClientInterface::class);
        $storageRedisConfigMock = $this->createMock(StorageRedisConfig::class);
        $storageRedisConfigMock->method('getRedisConnectionKey')->willReturn(static::CONNECTION_KEY);

        $this->storageRedisWrapper = new StorageRedisWrapper(
            $this->redisClientMock,
            $storageRedisConfigMock
        );
    }

    /**
     * @return void
     */
    public function testCanGetSameValueWhenFailedToJsonDecode(): void
    {
        $this->redisClientMock->method('get')->willReturnCallback(function ($connectionKey, $key) {
            return $this->dummyStorage[$key];
        });

        $result = $this->storageRedisWrapper->get(static::PLAIN_TEXT_KEY);

        $this->assertEquals(static::PLAIN_TEXT_DATA, $result);
    }

    /**
     * @return void
     */
    public function testCanGetDecodedJsonData(): void
    {
        $this->redisClientMock->method('get')->willReturnCallback(function ($connectionKey, $key) {
            return $this->dummyStorage[$key];
        });

        $result = $this->storageRedisWrapper->get(static::JSON_KEY);

        $this->assertIsArray($result);
        $this->assertEquals(
            json_decode(static::JSON_DATA, true),
            $result
        );
    }

    /**
     * @return void
     */
    public function testReadStatsAreUpdatedWhenInDebugMode(): void
    {
        $this->storageRedisWrapper->get(static::PLAIN_TEXT_KEY);
        $accessStats = $this->storageRedisWrapper->getAccessStats();
        $this->assertEquals(0, $accessStats['count']['read']);
        $this->assertEmpty($accessStats['keys']['read']);

        $this->storageRedisWrapper->setDebug(true);

        $this->storageRedisWrapper->get(static::PLAIN_TEXT_KEY);
        $accessStats = $this->storageRedisWrapper->getAccessStats();
        $this->assertEquals(1, $accessStats['count']['read']);
        $this->assertEquals(
            $this->addPrefixToKey(static::PLAIN_TEXT_KEY),
            array_pop($accessStats['keys']['read'])
        );
    }

    /**
     * @return void
     */
    public function testGetsMultiValuesWithNoKeysReturnsEmptyArray(): void
    {
        $result = $this->storageRedisWrapper->getMulti([]);

        $this->assertEmpty($result);
    }

    /**
     * @return void
     */
    public function testGetsMultiValues(): void
    {
        $this->redisClientMock->method('mget')->willReturnCallback(function ($connectionKey, $keys) {
            return array_map(function ($key) {
                return $this->dummyStorage[$key];
            }, $keys);
        });

        $result = $this->storageRedisWrapper->getMulti([static::PLAIN_TEXT_KEY]);
        $prefixedKey = $this->addPrefixToKey(static::PLAIN_TEXT_KEY);
        $this->assertIsArray($result);
        $this->assertArrayHasKey($prefixedKey, $result);
        $this->assertEquals(static::PLAIN_TEXT_DATA, $result[$prefixedKey]);
    }

    /**
     * @return void
     */
    public function testGetsStats(): void
    {
        $section = 'section';

        $this->redisClientMock
            ->expects($this->once())
            ->method('info')
            ->with(
                $this->equalTo(static::CONNECTION_KEY),
                $this->equalTo($section)
            );

        $this->storageRedisWrapper->getStats($section);
    }

    /**
     * @return void
     */
    public function testGetsAllKeys(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('keys')
            ->with(
                $this->equalTo(static::CONNECTION_KEY),
                $this->equalTo(
                    $this->addPrefixToKey('*')
                )
            );

        $this->storageRedisWrapper->getAllKeys();
    }

    /**
     * @return void
     */
    public function testGetsItemsCount(): void
    {
        $itemsCount = rand(1, 10);
        $this->redisClientMock->method('keys')->willReturn(range(1, $itemsCount));

        $this->assertEquals($itemsCount, $this->storageRedisWrapper->getCountItems());
    }

    /**
     * @return void
     */
    public function testCallsSetWithNoTtl(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('set')
            ->with(
                $this->equalTo(static::CONNECTION_KEY),
                $this->equalTo(
                    $this->addPrefixToKey(static::PLAIN_TEXT_KEY)
                ),
                static::PLAIN_TEXT_DATA
            )
            ->willReturn(true);

        $this->storageRedisWrapper->set(static::PLAIN_TEXT_KEY, static::PLAIN_TEXT_DATA);
    }

    /**
     * @return void
     */
    public function testCallsSetexWithTtlSet(): void
    {
        $ttl = 1;
        $this->redisClientMock
            ->expects($this->once())
            ->method('setex')
            ->with(
                $this->equalTo(static::CONNECTION_KEY),
                $this->equalTo(
                    $this->addPrefixToKey(static::PLAIN_TEXT_KEY)
                ),
                $this->equalTo($ttl),
                static::PLAIN_TEXT_DATA
            )
            ->willReturn(true);

        $this->storageRedisWrapper->set(static::PLAIN_TEXT_KEY, static::PLAIN_TEXT_DATA, $ttl);
    }

    /**
     * @expectedException \Spryker\Client\StorageRedis\Exception\StorageRedisException
     * @expectedExceptionMessage Could not set redisKey: "kv:plainTextKey" with value: ""plain text data""
     *
     * @return void
     */
    public function testWillThrowExceptionWhenEmptyResultIsReturnedBySet(): void
    {
        $this->storageRedisWrapper->set(static::PLAIN_TEXT_KEY, static::PLAIN_TEXT_DATA);
    }

    /**
     * @return void
     */
    public function testWriteAccessStatsAreUpdatedWhenInDebugMode(): void
    {
        $this->redisClientMock->method('set')->willReturn(true);

        $this->storageRedisWrapper->set(static::PLAIN_TEXT_KEY, static::PLAIN_TEXT_DATA);
        $accessStats = $this->storageRedisWrapper->getAccessStats();
        $this->assertEquals(0, $accessStats['count']['write']);
        $this->assertEmpty($accessStats['keys']['write']);

        $this->storageRedisWrapper->setDebug(true);

        $this->storageRedisWrapper->set(static::PLAIN_TEXT_KEY, static::PLAIN_TEXT_DATA);
        $accessStats = $this->storageRedisWrapper->getAccessStats();
        $this->assertEquals(1, $accessStats['count']['write']);
        $this->assertEquals(
            $this->addPrefixToKey(static::PLAIN_TEXT_KEY),
            array_pop($accessStats['keys']['write'])
        );
    }

    /**
     * @expectedException \Spryker\Client\StorageRedis\Exception\StorageRedisException
     * @expectedExceptionMessage Could not set redisKey: "kv:plainTextKey" with value: ""plain text data""
     *
     * @return void
     */
    public function testSetThrowsExceptionWhenResultIsFalsy(): void
    {
        $this->redisClientMock->method('setex')->willReturn(false);

        $this->storageRedisWrapper->set(static::PLAIN_TEXT_KEY, static::PLAIN_TEXT_DATA);
    }

    /**
     * @expectedException \Spryker\Client\StorageRedis\Exception\StorageRedisException
     * @expectedExceptionMessage Could not set redisKey: "kv:plainTextKey" with value: ""plain text data""
     *
     * @return void
     */
    public function testSetexWillThrowExceptionWhenResultIsFalsy(): void
    {
        $this->redisClientMock->method('setex')->willReturn(false);

        $this->storageRedisWrapper->set(static::PLAIN_TEXT_KEY, static::PLAIN_TEXT_DATA, 1);
    }

    /**
     * @return void
     */
    public function testSetMulti(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('mset')
            ->with(
                static::CONNECTION_KEY,
                $this->callback(function ($data) {
                    return $this->dummyStorage === $data;
                })
            )
            ->willReturn(true);

        $items = array_combine(
            [static::PLAIN_TEXT_KEY, static::JSON_KEY],
            [static::PLAIN_TEXT_DATA, static::JSON_DATA]
        );

        $this->storageRedisWrapper->setMulti($items);
    }

    /**
     * @return void
     */
    public function testSetMultiReturnsEarlyWhenNoItems(): void
    {
        $this->redisClientMock->expects($this->never())->method('mset');

        $this->storageRedisWrapper->setMulti([]);
    }

    /**
     * @expectedException \Spryker\Client\StorageRedis\Exception\StorageRedisException
     *
     * @return void
     */
    public function testSetMultiThrowsExceptionWhenResultIsFalsy(): void
    {
        $items = array_combine(
            [static::PLAIN_TEXT_KEY, static::JSON_KEY],
            [static::PLAIN_TEXT_DATA, static::JSON_DATA]
        );

        $this->redisClientMock->method('mset')->willReturn(false);

        $this->storageRedisWrapper->setMulti($items);
    }

    /**
     * @return void
     */
    public function testCanDeleteData(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('del')
            ->with(
                $this->equalTo(static::CONNECTION_KEY),
                $this->equalTo([
                    $this->addPrefixToKey(static::PLAIN_TEXT_KEY),
                ])
            );
        $keyToDelete = static::PLAIN_TEXT_KEY;

        $this->storageRedisWrapper->delete($keyToDelete);
    }

    /**
     * @return void
     */
    public function testCanDeleteMultiData(): void
    {
        $this->redisClientMock
            ->expects($this->once())
            ->method('del')
            ->with(
                $this->equalTo(static::CONNECTION_KEY),
                $this->callback(function ($keys) {
                    return $keys === array_keys($this->dummyStorage);
                })
            );
        $keysToDelete = [static::PLAIN_TEXT_KEY, static::JSON_KEY];

        $this->storageRedisWrapper->deleteMulti($keysToDelete);
    }

    /**
     * @return void
     */
    public function testCanDeleteAllData(): void
    {
        $this->redisClientMock->method('keys')->willReturnCallback(function () {
            return array_keys($this->dummyStorage);
        });
    }

    /**
     * @return void
     */
    protected function setupDummyStorage(): void
    {
        $this->dummyStorage = [
            $this->addPrefixToKey(static::PLAIN_TEXT_KEY) => static::PLAIN_TEXT_DATA,
            $this->addPrefixToKey(static::JSON_KEY) => static::JSON_DATA,
        ];
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function addPrefixToKey(string $key): string
    {
        return sprintf('%s:%s', static::KEY_PREFIX, $key);
    }
}
