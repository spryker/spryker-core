<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Redis\Adapter;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Generated\Shared\Transfer\RedisCredentialsTransfer;
use Spryker\Client\Redis\Adapter\LoggableRedisAdapter;
use Spryker\Client\Redis\Adapter\RedisAdapterInterface;
use Spryker\Shared\Redis\Logger\RedisLoggerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Redis
 * @group Adapter
 * @group LoggableRedisAdapterTest
 * Add your own group annotations below this line
 */
class LoggableRedisAdapterTest extends Unit
{
    protected const PROTOCOL = 'redis';
    protected const HOST = 'localhost';
    protected const PORT = 6379;
    protected const DATABASE = 1;

    /**
     * @var \Spryker\Client\Redis\Adapter\RedisAdapterInterface
     */
    protected $redisAdapterMock;

    /**
     * @var \Spryker\Shared\Redis\Logger\RedisLoggerInterface
     */
    protected $redisLoggerMock;

    /**
     * @var \Spryker\Client\Redis\Adapter\RedisAdapterInterface
     */
    protected $loggableRedisAdapter;

    /**
     * @return void
     */
    protected function _setUp()
    {
        parent::_setUp();

        $this->redisAdapterMock = $this->createMock(RedisAdapterInterface::class);
        $this->redisLoggerMock = $this->createMock(RedisLoggerInterface::class);
        $this->setupLoggableRedisAdapter();
    }

    /**
     * @return void
     */
    public function testCanHandleGetCall(): void
    {
        $key = 'redis:key';
        $returnValue = null;

        $this->redisAdapterMock->expects($this->once())->method('get')->willReturn($returnValue);
        $this->redisLoggerMock->expects($this->once())
            ->method('logCall')
            ->with('redis://localhost:6379/1', 'GET', ['key' => $key], $returnValue);

        $this->loggableRedisAdapter->get($key);
    }

    /**
     * @return void
     */
    public function testCanHandleSetexCall(): void
    {
        $key = 'redis:key';
        $seconds = 1;
        $value = 'value';
        $returnValue = true;

        $this->redisAdapterMock->expects($this->once())->method('setex')->willReturn($returnValue);
        $this->redisLoggerMock->expects($this->once())
            ->method('logCall')
            ->with('redis://localhost:6379/1', 'SETEX', ['key' => $key, 'seconds' => $seconds, 'value' => $value], $returnValue);

        $this->loggableRedisAdapter->setex($key, $seconds, $value);
    }

    /**
     * @return void
     */
    public function testCanHandleSetCall(): void
    {
        $key = 'redis:key';
        $value = 'value';
        $expireResolution = 'expireResolution';
        $expireTTL = 1;
        $flag = 'flag';

        $this->redisAdapterMock->expects($this->once())->method('set')->willReturn(true);
        $this->redisLoggerMock->expects($this->once())
            ->method('logCall')
            ->with(
                'redis://localhost:6379/1',
                'SET',
                [
                    'key' => $key,
                    'value' => $value,
                    'expireResolution' => $expireResolution,
                    'expireTTL' => $expireTTL,
                    'flag' => $flag,
                ],
                true
            );

        $this->loggableRedisAdapter->set(
            $key,
            $value,
            $expireResolution,
            $expireTTL,
            $flag
        );
    }

    /**
     * @return void
     */
    public function testCanHandleDelCall(): void
    {
        $keys = ['redis:key:1', 'redis:key:2', 'redis:key:3'];
        $returnValue = 1;

        $this->redisAdapterMock->expects($this->once())->method('del')->willReturn($returnValue);
        $this->redisLoggerMock->expects($this->once())
            ->method('logCall')
            ->with(
                'redis://localhost:6379/1',
                'DEL',
                [
                    'keys' => $keys,
                ],
                $returnValue
            );

        $this->loggableRedisAdapter->del($keys);
    }

    /**
     * @return void
     */
    public function testCanHandleEvalCall(): void
    {
        $script = 'script';
        $numKeys = 1;
        $keysOrArgs = ['keysOrArgs'];
        $returnValue = true;

        $this->redisAdapterMock->expects($this->once())->method('eval')->willReturn($returnValue);
        $this->redisLoggerMock->expects($this->once())
            ->method('logCall')
            ->with(
                'redis://localhost:6379/1',
                'EVAL',
                [
                    'script' => $script,
                    'numKeys' => $numKeys,
                    'keysOrArgs' => $keysOrArgs,
                ],
                $returnValue
            );

        $this->loggableRedisAdapter->eval($script, $numKeys, $keysOrArgs);
    }

    /**
     * @return void
     */
    public function testCanHandleMgetCall(): void
    {
        $keys = ['redis:key:1', 'redis:key:2', 'redis:key:3'];
        $returnValue = [];

        $this->redisAdapterMock->expects($this->once())->method('mget')->willReturn($returnValue);
        $this->redisLoggerMock->expects($this->once())
            ->method('logCall')
            ->with(
                'redis://localhost:6379/1',
                'MGET',
                [
                    'keys' => $keys,
                ],
                $returnValue
            );

        $this->loggableRedisAdapter->mget($keys);
    }

    /**
     * @return void
     */
    public function testCanHandleMsetCall(): void
    {
        $dictionary = ['redis:key:1' => 'one', 'redis:key:2' => 'two', 'redis:key:3' => 'three'];
        $returnValue = true;

        $this->redisAdapterMock->expects($this->once())->method('mset')->willReturn($returnValue);
        $this->redisLoggerMock->expects($this->once())
            ->method('logCall')
            ->with(
                'redis://localhost:6379/1',
                'MSET',
                [
                    'dictionary' => $dictionary,
                ],
                $returnValue
            );

        $this->loggableRedisAdapter->mset($dictionary);
    }

    /**
     * @return void
     */
    public function testCanHandleInfoCall(): void
    {
        $section = 'section';
        $returnValue = [];

        $this->redisAdapterMock->expects($this->once())->method('info')->willReturn($returnValue);
        $this->redisLoggerMock->expects($this->once())
            ->method('logCall')
            ->with(
                'redis://localhost:6379/1',
                'INFO',
                [
                    'section' => $section,
                ],
                $returnValue
            );

        $this->loggableRedisAdapter->info($section);
    }

    /**
     * @return void
     */
    public function testCanHandleKeysCall(): void
    {
        $pattern = 'key:*';
        $returnValue = [];

        $this->redisAdapterMock->expects($this->once())->method('keys')->willReturn($returnValue);
        $this->redisLoggerMock->expects($this->once())
            ->method('logCall')
            ->with(
                'redis://localhost:6379/1',
                'KEYS',
                [
                    'pattern' => $pattern,
                ],
                $returnValue
            );

        $this->loggableRedisAdapter->keys($pattern);
    }

    /**
     * @return void
     */
    public function testCanHandleScanCall(): void
    {
        $cursor = 1;
        $options = ['option1', 'option2'];
        $returnValue = [];

        $this->redisAdapterMock->expects($this->once())->method('scan')->willReturn($returnValue);
        $this->redisLoggerMock->expects($this->once())
            ->method('logCall')
            ->with(
                'redis://localhost:6379/1',
                'SCAN',
                [
                    'cursor' => $cursor,
                    'options' => $options,
                ],
                $returnValue
            );

        $this->loggableRedisAdapter->scan($cursor, $options);
    }

    /**
     * @return void
     */
    public function testCanHandleDbSizeCall(): void
    {
        $returnValue = 1;

        $this->redisAdapterMock->expects($this->once())->method('dbSize')->willReturn($returnValue);
        $this->redisLoggerMock->expects($this->once())
            ->method('logCall')
            ->with('redis://localhost:6379/1', 'DBSIZE', [], $returnValue);

        $this->loggableRedisAdapter->dbSize();
    }

    /**
     * @return void
     */
    public function testCanHandleFlushDbCall(): void
    {
        $this->redisAdapterMock->expects($this->once())->method('flushDb');
        $this->redisLoggerMock->expects($this->once())
            ->method('logCall')
            ->with('redis://localhost:6379/1', 'FLUSHDB', []);

        $this->loggableRedisAdapter->flushDb();
    }

    /**
     * @return void
     */
    protected function setupLoggableRedisAdapter(): void
    {
        $connectionCredentials = (new RedisCredentialsTransfer())
            ->setProtocol(static::PROTOCOL)
            ->setHost(static::HOST)
            ->setPort(static::PORT)
            ->setDatabase(static::DATABASE);
        $configurationTransfer = (new RedisConfigurationTransfer())
            ->setConnectionCredentials($connectionCredentials);

        $this->loggableRedisAdapter = new LoggableRedisAdapter(
            $configurationTransfer,
            $this->redisAdapterMock,
            $this->redisLoggerMock
        );
    }
}
