<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Redis\Logger;

use Codeception\Test\Unit;
use SprykerTest\Shared\Redis\RedisClientTester;

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
     * @dataProvider canLogCallsDataProvider
     *
     * @param array $callData
     * @param array $expectedResult
     *
     * @return void
     */
    public function testCanLogCalls(array $callData, array $expectedResult): void
    {
        // Arrange
        $redisInMemoryLogger = $this->tester->createRedisInMemoryLogger();

        // Act
        foreach ($callData as $singleCallData) {
            $redisInMemoryLogger->log(
                $singleCallData['command'],
                $singleCallData['payload'],
                $singleCallData['result'],
            );
        }

        // Assert
        $this->assertSame($expectedResult, $redisInMemoryLogger->getLogs());
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
                        'command' => 'GET',
                        'payload' => ['key' => 'some:redis:key'],
                        'result' => ['result'],
                    ],
                ],
                [
                    [
                        'destination' => $this->buildDsnString(),
                        'command' => 'GET',
                        'payload' => json_encode(['key' => 'some:redis:key'], JSON_PRETTY_PRINT),
                        'result' => json_encode(['result'], JSON_PRETTY_PRINT),
                    ],
                ],
            ],
            'another calls' => [
                [
                    [
                        'command' => 'SET',
                        'payload' => ['key' => 'some:redis:key', 'data' => ['dummy data']],
                        'result' => ['result'],
                    ],
                ],
                [
                    [
                        'destination' => $this->buildDsnString(),
                        'command' => 'GET',
                        'payload' => json_encode(['key' => 'some:redis:key'], JSON_PRETTY_PRINT),
                        'result' => json_encode(['result'], JSON_PRETTY_PRINT),
                    ],
                    [
                        'destination' => $this->buildDsnString(),
                        'command' => 'SET',
                        'payload' => json_encode(['key' => 'some:redis:key', 'data' => ['dummy data']], JSON_PRETTY_PRINT),
                        'result' => json_encode(['result'], JSON_PRETTY_PRINT),
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string|null $protocol
     * @param string|null $host
     * @param int|null $port
     * @param string|null $database
     *
     * @return string
     */
    public function buildDsnString(?string $protocol = null, ?string $host = null, ?int $port = null, ?string $database = null): string
    {
        return sprintf(
            '%s://%s:%d/%s',
            $protocol ?? RedisClientTester::DEFAULT_REDIS_PROTOCOL,
            $host ?? RedisClientTester::DEFAULT_REDIS_HOST,
            $port ?? RedisClientTester::DEFAULT_REDIS_PORT,
            $database ?? RedisClientTester::DEFAULT_REDIS_DATABASE
        );
    }
}
