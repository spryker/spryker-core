<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Redis\Adapter;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Predis\Client;
use Spryker\Client\Redis\Adapter\Factory\PredisAdapterFactory;
use Spryker\Client\Redis\Compressor\Strategy\ZlibCompressorStrategy;
use SprykerTest\Client\Redis\RedisClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Redis
 * @group Adapter
 * @group RedisClientTest
 * Add your own group annotations below this line
 */
class RedisClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\Redis\RedisClientTester
     */
    protected RedisClientTester $tester;

    /**
     * @dataProvider getKeyValueReadDataProvider
     *
     * @param mixed $expectedValue
     * @param mixed $redisValue
     *
     * @return void
     */
    public function testCanHandleGetCallWhenCompressingIsDisabledAndRedisCompressedData(mixed $expectedValue, mixed $redisValue): void
    {
        // Arrange
        $client = $this->configureAndMockClient(false);

        // Assert
        $client->expects($this->once())
            ->method('__call')
            ->with('get', [RedisClientTester::KEY])
            ->willReturn($redisValue);

        // Act
        $result = $this->tester->getClient()->get('predis', RedisClientTester::KEY);

        // Assert
        $this->assertEquals($expectedValue, $result);
    }

    /**
     * @dataProvider getKeyValueWriteDataProvider
     *
     * @param string $originalValue
     * @param mixed $expectedValue
     *
     * @return void
     */
    public function testCanHandleSetexCall(string $originalValue, mixed $expectedValue): void
    {
        // Arrange
        $client = $this->configureAndMockClient();

        // Assert
        $client->expects($this->once())
            ->method('__call')
            ->with('setex', [RedisClientTester::KEY, 1, $expectedValue])
            ->willReturn(true);

        // Act
        $this->tester->getClient()->setex('predis', RedisClientTester::KEY, 1, $originalValue);
    }

    /**
     * @dataProvider getKeyValueWriteDataProvider
     *
     * @param string $originalValue
     * @param mixed $expectedValue
     *
     * @return void
     */
    public function testCanHandleSetCall(string $originalValue, mixed $expectedValue): void
    {
        // Arrange
        $client = $this->configureAndMockClient();

        // Assert
        $client->expects($this->once())
            ->method('__call')
            ->with('set', [RedisClientTester::KEY, $expectedValue])
            ->willReturn(true);

        // Act
        $this->tester->getClient()->set('predis', RedisClientTester::KEY, $originalValue);
    }

    /**
     * @return void
     */
    public function testCanHandleDelCall(): void
    {
        // Arrange
        $client = $this->configureAndMockClient();
        $keys = [RedisClientTester::KEY];

        // Assert
        $client->expects($this->once())
            ->method('__call')
            ->with('del', [$keys])
            ->willReturn(1);

        // Act
        $this->tester->getClient()->del('predis', $keys);
    }

    /**
     * @return void
     */
    public function testCanHandleEvalCall(): void
    {
        // Arrange
        $client = $this->configureAndMockClient();
        $script = 'script';
        $numKeys = 1;
        $keysOrArgs = ['keysOrArgs'];

        // Assert
        $client->expects($this->once())
            ->method('__call')
            ->with('eval', [$script, $numKeys, $keysOrArgs])
            ->willReturn(true);

        // Act
        $this->tester->getClient()->eval('predis', $script, $numKeys, $keysOrArgs);
    }

    /**
     * @dataProvider getKeyValueReadDataProvider
     *
     * @param mixed $expectedValue
     * @param mixed $redisValue
     *
     * @return void
     */
    public function testCanHandleMgetCall(mixed $expectedValue, mixed $redisValue): void
    {
        // Arrange
        $client = $this->configureAndMockClient();
        $keys = [RedisClientTester::KEY];
        $returnValue = [$expectedValue];

        // Assert
        $client->expects($this->once())
            ->method('__call')
            ->with('mget', [[RedisClientTester::KEY]])
            ->willReturn([RedisClientTester::KEY => $redisValue]);

        // Act
        $result = $this->tester->getClient()->mget('predis', $keys);

        // Assert
        $this->assertEquals([RedisClientTester::KEY => $expectedValue], $result);
    }

    /**
     * @dataProvider getKeyValueWriteDataProvider
     *
     * @param string $originalValue
     * @param mixed $expectedValue
     *
     * @return void
     */
    public function testCanHandleMsetCall(string $originalValue, mixed $expectedValue): void
    {
        // Arrange
        $client = $this->configureAndMockClient();
        $dictionary = [RedisClientTester::KEY => $originalValue];

        // Assert
        $client->expects($this->once())
            ->method('__call')
            ->with('mset', [[RedisClientTester::KEY => $expectedValue]])
            ->willReturn(true);

        // Act
        $this->tester->getClient()->mset('predis', $dictionary);
    }

    /**
     * @return void
     */
    public function testCanHandleInfoCall(): void
    {
        // Arrange
        $client = $this->configureAndMockClient();
        $section = 'section';

        // Assert
        $client->expects($this->once())
            ->method('__call')
            ->with('info', [$section])
            ->willReturn([]);

        // Act
        $this->tester->getClient()->info('predis', $section);
    }

    /**
     * @return void
     */
    public function testCanHandleKeysCall(): void
    {
        // Arrange
        $client = $this->configureAndMockClient();

        // Assert
        $client->expects($this->once())
            ->method('__call')
            ->with('keys', [RedisClientTester::KEY])
            ->willReturn([]);

        // Act
        $this->tester->getClient()->keys('predis', RedisClientTester::KEY);
    }

    /**
     * @return void
     */
    public function testCanHandleScanCall(): void
    {
        // Arrange
        $client = $this->configureAndMockClient();
        $cursor = 1;
        $options = ['option1', 'option2'];

        // Assert
        $client->expects($this->once())
            ->method('__call')
            ->with('scan', [$cursor, $options])
            ->willReturn([]);

        // Act
        $this->tester->getClient()->scan('predis', $cursor, $options);
    }

    /**
     * @return void
     */
    public function testCanHandleDbSizeCall(): void
    {
        // Arrange
        $client = $this->configureAndMockClient();

        // Assert
        $client->expects($this->once())
            ->method('__call')
            ->with('dbsize')
            ->willReturn(1);

        // Act
        $this->tester->getClient()->dbSize('predis');
    }

    /**
     * @return void
     */
    public function testCanHandleFlushDbCall(): void
    {
        // Arrange
        $client = $this->configureAndMockClient();

        // Assert
        $client->expects($this->once())
            ->method('__call')
            ->with('flushdb');

        // Act
        $this->tester->getClient()->flushDb('predis');
    }

    /**
     * @return void
     */
    public function testCanHandleIncr(): void
    {
        // Arrange
        $client = $this->configureAndMockClient();

        // Assert
        $client->expects($this->once())
            ->method('__call')
            ->with('incr')
            ->willReturn(1);

        // Act
        $this->tester->getClient()->incr('predis', RedisClientTester::KEY);
    }

    /**
     * @param bool $isCompressionEnabled
     *
     * @return \SprykerTest\Client\Redis\Adapter\PHPUnit\Framework\MockObject\MockObject|\Predis\Client
     */
    protected function configureAndMockClient(bool $isCompressionEnabled = true): Client
    {
        $this->tester->resetClientPool();
        $this->tester->mockConfigMethod('isCompressionEnabled', $isCompressionEnabled);
        $this->tester->mockConfigMethod('getMinBytesForCompression', 5);
        $this->tester->mockFactoryMethod('getKeyValueCompressorStrategies', [
            new ZlibCompressorStrategy(), // main strategy for compression
            $this->tester->createTestCompressorStrategy(), // BC strategy for uncompression when redis contains the data by currret compressor
        ]);
        $this->tester->mockFactoryMethod('getConfig', $this->tester->getModuleConfig());
        $client = $this->createMock(Client::class);

        $predisAdapterFactory = $this->getMockBuilder(PredisAdapterFactory::class)
            ->setConstructorArgs([
                $this->tester->getFactory()->getConfig(),
                $this->tester->getFactory()->getUtilEncodingService(),
                $this->tester->getFactory()->createCompressor(),
            ])
            ->onlyMethods(['createPredisClient'])
            ->getMock();
        $predisAdapterFactory->method('createPredisClient')->willReturn($client);

        $this->tester->mockFactoryMethod('createRedisAdapterFactory', $predisAdapterFactory);
        $this->tester->getClient()->setupConnection('predis', new RedisConfigurationTransfer());

        return $client;
    }

    /**
     * @return array<array<string>>
     */
    public function getKeyValueReadDataProvider(): array
    {
        return [
            ['value_gzencode', gzencode('value_gzencode')],
            ['value', 'value'], // should skip, because of getMinBytesForCompression()
            ['value_1', 'customvalue_1'], // for the second custom compressor
            [null, null],
            ['', ''],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public function getKeyValueWriteDataProvider(): array
    {
        return [
            ['value_gzencode', gzencode('value_gzencode')],
            ['value', 'value'], // should skip, because of getMinBytesForCompression()
        ];
    }
}
