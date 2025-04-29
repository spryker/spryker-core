<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Redis\Adapter;

use Codeception\Test\Unit;
use Spryker\Client\Redis\Adapter\PredisCompressionAdapter;
use Spryker\Client\Redis\Adapter\RedisAdapterInterface;
use Spryker\Client\Redis\Compressor\Strategy\ZlibCompressorStrategy;
use SprykerTest\Client\Redis\RedisClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Redis
 * @group Adapter
 * @group PredisCompressionAdapterTest
 * Add your own group annotations below this line
 */
class PredisCompressionAdapterTest extends Unit
{
    /**
     * @var \SprykerTest\Client\Redis\RedisClientTester
     */
    protected RedisClientTester $tester;

    /**
     * @var \Spryker\Client\Redis\Adapter\RedisAdapterInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $redisAdapterMock;

    /**
     * @var \Spryker\Client\Redis\Adapter\RedisAdapterInterface
     */
    protected $pRedisCompressionAdapter;

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
        $this->setupPredisCompressionAdapter(false);

        // Assert
        $this->redisAdapterMock->expects($this->once())
            ->method('get')
            ->with(RedisClientTester::KEY)
            ->willReturn($redisValue);

        // Act
        $result = $this->pRedisCompressionAdapter->get(RedisClientTester::KEY);

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
        $this->setupPredisCompressionAdapter();

        // Assert
        $this->redisAdapterMock->expects($this->once())
            ->method('setex')
            ->with(RedisClientTester::KEY, 1, $expectedValue)
            ->willReturn(true);

        // Act
        $this->pRedisCompressionAdapter->setex(RedisClientTester::KEY, 1, $originalValue);
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
        $this->setupPredisCompressionAdapter();

        // Assert
        $this->redisAdapterMock->expects($this->once())
            ->method('set')
            ->with(RedisClientTester::KEY, $expectedValue)
            ->willReturn(true);

        // Act
        $this->pRedisCompressionAdapter->set(RedisClientTester::KEY, $originalValue);
    }

    /**
     * @return void
     */
    public function testCanHandleDelCall(): void
    {
        // Arrange
        $this->setupPredisCompressionAdapter();
        $keys = [RedisClientTester::KEY];

        // Assert
        $this->redisAdapterMock->expects($this->once())
            ->method('del')
            ->with($keys)
            ->willReturn(1);

        // Act
        $this->pRedisCompressionAdapter->del($keys);
    }

    /**
     * @return void
     */
    public function testCanHandleEvalCall(): void
    {
        // Arrange
        $this->setupPredisCompressionAdapter();
        $script = 'script';
        $numKeys = 1;
        $keysOrArgs = ['keysOrArgs'];

        // Assert
        $this->redisAdapterMock->expects($this->once())
            ->method('eval')
            ->with($script, $numKeys, $keysOrArgs)
            ->willReturn(true);

        // Act
        $this->pRedisCompressionAdapter->eval($script, $numKeys, $keysOrArgs);
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
        $this->setupPredisCompressionAdapter();
        $keys = [RedisClientTester::KEY];
        $returnValue = [$expectedValue];

        // Assert
        $this->redisAdapterMock->expects($this->once())->method('mget')->willReturn([RedisClientTester::KEY => $redisValue]);

        // Act
        $result = $this->pRedisCompressionAdapter->mget($keys);

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
        $this->setupPredisCompressionAdapter();
        $dictionary = [RedisClientTester::KEY => $originalValue];

        // Assert
        $this->redisAdapterMock->expects($this->once())
            ->method('mset')
            ->with([RedisClientTester::KEY => $expectedValue])
            ->willReturn(true);

        // Act
        $this->pRedisCompressionAdapter->mset($dictionary);
    }

    /**
     * @return void
     */
    public function testCanHandleInfoCall(): void
    {
        // Arrange
        $this->setupPredisCompressionAdapter();
        $section = 'section';

        // Assert
        $this->redisAdapterMock->expects($this->once())
            ->method('info')
            ->with($section)
            ->willReturn([]);

        // Act
        $this->pRedisCompressionAdapter->info($section);
    }

    /**
     * @return void
     */
    public function testCanHandleKeysCall(): void
    {
        // Arrange
        $this->setupPredisCompressionAdapter();

        // Assert
        $this->redisAdapterMock->expects($this->once())
            ->method('keys')
            ->with(RedisClientTester::KEY)
            ->willReturn([]);

        // Act
        $this->pRedisCompressionAdapter->keys(RedisClientTester::KEY);
    }

    /**
     * @return void
     */
    public function testCanHandleScanCall(): void
    {
        // Arrange
        $this->setupPredisCompressionAdapter();
        $cursor = 1;
        $options = ['option1', 'option2'];

        // Assert
        $this->redisAdapterMock->expects($this->once())
            ->method('scan')
            ->with($cursor, $options)
            ->willReturn([]);

        // Act
        $this->pRedisCompressionAdapter->scan($cursor, $options);
    }

    /**
     * @return void
     */
    public function testCanHandleDbSizeCall(): void
    {
        // Arrange
        $this->setupPredisCompressionAdapter();

        // Assert
        $this->redisAdapterMock->expects($this->once())->method('dbSize')->willReturn(1);

        // Act
        $this->pRedisCompressionAdapter->dbSize();
    }

    /**
     * @return void
     */
    public function testCanHandleFlushDbCall(): void
    {
        // Arrange
        $this->setupPredisCompressionAdapter();

        // Assert
        $this->redisAdapterMock->expects($this->once())->method('flushDb');

        // Act
        $this->pRedisCompressionAdapter->flushDb();
    }

    /**
     * @return void
     */
    public function testCanHandleIncr(): void
    {
        // Arrange
        $this->setupPredisCompressionAdapter();

        // Assert
        $this->redisAdapterMock->expects($this->once())->method('incr');

        // Act
        $this->pRedisCompressionAdapter->incr(RedisClientTester::KEY);
    }

    /**
     * @param bool $isCompressionEnabled
     *
     * @return void
     */
    protected function setupPredisCompressionAdapter(bool $isCompressionEnabled = true): void
    {
        $this->tester->mockConfigMethod('isCompressionEnabled', $isCompressionEnabled);
        $this->tester->mockConfigMethod('getMinBytesForCompression', 3);
        $this->tester->mockFactoryMethod('getKeyValueCompressorStrategies', [
            new ZlibCompressorStrategy(), // main strategy for compression
            $this->tester->createTestCompressorStrategy(), // BC strategy for uncompression when redis contains the data by currret compressor
        ]);
        $this->redisAdapterMock = $this->createMock(RedisAdapterInterface::class);

        /** @var \Spryker\Client\Redis\RedisFactory $factory */
        $factory = $this->tester->getFactory();
        $factory->setConfig($this->tester->getModuleConfig());
        $this->pRedisCompressionAdapter = new PredisCompressionAdapter(
            $this->redisAdapterMock,
            $factory->createCompressor(),
        );
    }

    /**
     * @return array<array<string>>
     */
    public function getKeyValueReadDataProvider(): array
    {
        return [
            ['key1', gzencode('key1')],
            ['key', 'key'], // should skip, because of getMinBytesForCompression()
            ['key3', 'customkey3'], // for the second custom compressor
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
            ['key1', gzencode('key1')],
            ['key', 'key'], // should skip, because of getMinBytesForCompression()
        ];
    }
}
