<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SearchElasticsearch\Logger;

use Codeception\Test\Unit;
use SprykerTest\Shared\SearchElasticsearch\SearchElasticsearchSharedTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group SearchElasticsearch
 * @group Logger
 * @group ElasticsearchInMemoryLoggerTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Shared\SearchElasticsearch\SearchElasticsearchSharedTester $tester
 */
class ElasticsearchInMemoryLoggerTest extends Unit
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
        $elasticsearchInMemoryLogger = $this->tester->createElasticsearchInMemoryLogger();

        // Act
        foreach ($callData as $singleCallData) {
            $elasticsearchInMemoryLogger->log(
                $singleCallData['payload'],
                $singleCallData['result'],
            );
        }

        // Assert
        $this->assertSame($expectedResult, $elasticsearchInMemoryLogger->getLogs());
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
                        'payload' => [
                            'query' => [
                                'query-match-all' => [],
                            ],
                        ],
                        'result' => [
                            'product_reviews' => [],
                        ],
                    ],
                ],
                [
                    [
                        'destination' => $this->buildUriString(),
                        'payload' => json_encode([
                            'query' => [
                                'query-match-all' => [],
                            ],
                        ], JSON_PRETTY_PRINT),
                        'result' => json_encode([
                            'product_reviews' => [],
                        ], JSON_PRETTY_PRINT),
                    ],
                ],
            ],
            'another calls' => [
                [
                    [
                        'payload' => [
                            'query' => [
                                'bool' => [],
                            ],
                        ],
                        'result' => [
                            'product_reviews' => ['review'],
                        ],
                    ],
                ],
                [
                    [
                        'destination' => $this->buildUriString(),
                        'payload' => json_encode([
                            'query' => [
                                'query-match-all' => [],
                            ],
                        ], JSON_PRETTY_PRINT),
                        'result' => json_encode([
                            'product_reviews' => [],
                        ], JSON_PRETTY_PRINT),
                    ],
                    [
                        'destination' => $this->buildUriString(),
                        'payload' => json_encode([
                            'query' => [
                                'bool' => [],
                            ],
                        ], JSON_PRETTY_PRINT),
                        'result' => json_encode([
                            'product_reviews' => ['review'],
                        ], JSON_PRETTY_PRINT),
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string|null $protocol
     * @param string|null $host
     * @param int|null $port
     *
     * @return string
     */
    public function buildUriString(?string $protocol = null, ?string $host = null, ?int $port = null): string
    {
        return sprintf(
            '%s://%s:%d',
            $protocol ?? SearchElasticsearchSharedTester::DEFAULT_ELASTICSEARCH_PROTOCOL,
            $host ?? SearchElasticsearchSharedTester::DEFAULT_ELASTICSEARCH_HOST,
            $port ?? SearchElasticsearchSharedTester::DEFAULT_ELASTICSEARCH_PORT,
        );
    }
}
