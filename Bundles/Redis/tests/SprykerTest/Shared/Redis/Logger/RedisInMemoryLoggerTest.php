<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Redis\Logger;

use Codeception\Test\Unit;
use Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceBridge;
use Spryker\Shared\Redis\Logger\RedisInMemoryLogger;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Redis
 * @group Logger
 * @group RedisInMemoryLoggerTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Shared\Redis\RedisClientTester $tester
 */
class RedisInMemoryLoggerTest extends Unit
{
    /**
     * @var \Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface
     */
    protected $redisToUtilEncodingServiceBridge;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $this->redisToUtilEncodingServiceBridge = new RedisToUtilEncodingServiceBridge(
            $this->tester->getLocator()->utilEncoding()->service()
        );
    }

    /**
     * @dataProvider canLogCallsDataProvider
     *
     * @param array $callData
     * @param array $expectedResult
     *
     * @return void
     */
    public function testCanLogCalls(array $callData, array $expectedResult): void
    {
        $redisInMemoryLogger = new RedisInMemoryLogger(
            $this->redisToUtilEncodingServiceBridge
        );

        foreach ($callData as $singleCallData) {
            $redisInMemoryLogger->logCall(
                $singleCallData['destination'],
                $singleCallData['command'],
                $singleCallData['payload'],
                $singleCallData['result'],
            );
        }

        $this->assertSame($expectedResult, $redisInMemoryLogger->getCalls());
    }

    /**
     * @return array
     */
    public function canLogCallsDataProvider(): array
    {
        return [
            'first call' => [
                [
                    [
                        'destination' => 'redis://localhost:4321',
                        'command' => 'GET',
                        'payload' => ['key' => 'some:redis:key'],
                        'result' => ['result'],
                    ],
                ],
                [
                    [
                        'destination' => 'redis://localhost:4321',
                        'command' => 'GET',
                        'payload' => json_encode(['key' => 'some:redis:key'], JSON_PRETTY_PRINT),
                        'result' => json_encode(['result'], JSON_PRETTY_PRINT),
                    ],
                ],
            ],
            'another calls' => [
                [
                    [
                        'destination' => 'redis://localhost:4321',
                        'command' => 'SET',
                        'payload' => ['key' => 'some:redis:key', 'data' => ['dummy data']],
                        'result' => ['result'],
                    ],
                ],
                [
                    [
                        'destination' => 'redis://localhost:4321',
                        'command' => 'GET',
                        'payload' => json_encode(['key' => 'some:redis:key'], JSON_PRETTY_PRINT),
                        'result' => json_encode(['result'], JSON_PRETTY_PRINT),
                    ],
                    [
                        'destination' => 'redis://localhost:4321',
                        'command' => 'SET',
                        'payload' => json_encode(['key' => 'some:redis:key', 'data' => ['dummy data']], JSON_PRETTY_PRINT),
                        'result' => json_encode(['result'], JSON_PRETTY_PRINT),
                    ],
                ],
            ],
        ];
    }
}
