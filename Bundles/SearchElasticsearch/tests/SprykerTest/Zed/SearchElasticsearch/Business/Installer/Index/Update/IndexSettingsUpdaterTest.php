<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\SearchElasticsearch\tests\SprykerTest\Zed\SearchElasticsearch\Business\Installer\Index\Update;

use Codeception\Test\Unit;
use Elastica\Client;
use Elastica\Index;
use Generated\Shared\Transfer\IndexDefinitionTransfer;
use Psr\Log\NullLogger;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Update\IndexSettingsUpdater;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig;

/**
 * Auto-generated group annotations
 *
 * @group Spryker
 * @group SearchElasticsearch
 * @group tests
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Installer
 * @group Index
 * @group Update
 * @group IndexSettingsUpdaterTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\SearchElasticsearch\SearchElasticsearchZedTester $tester
 */
class IndexSettingsUpdaterTest extends Unit
{
    /**
     * @dataProvider canAcceptIndexDefinitionProvider
     *
     * @param bool $expectedResult
     * @param bool $indexExists
     * @param array $settings
     *
     * @return void
     */
    public function testCanAcceptIndexDefinition(bool $expectedResult, bool $indexExists, array $settings): void
    {
        $client = $this->createClientMock();
        /** @var \Elastica\Index\|\PHPUnit\Framework\MockObject\MockObject $index */
        $index = $client->getIndex('index');
        $index->method('exists')->willReturn($indexExists);
        $indexDefinitionTransfer = new IndexDefinitionTransfer();
        $indexDefinitionTransfer->setSettings($settings);
        $indexSettingsUpdater = $this->createIndexSettingsUpdated($client);

        $result = $indexSettingsUpdater->accept($indexDefinitionTransfer);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function canAcceptIndexDefinitionProvider(): array
    {
        return [
            'index exists empty settings' => [
                false,
                true,
                [],
            ],
            'index not exists empty settings' => [
                false,
                false,
                [],
            ],
            'index exists not empty settings' => [
                true,
                true,
                ['settings'],
            ],
            'index not exists not empty settings' => [
                false,
                false,
                ['settings'],
            ],
        ];
    }

    /**
     * @dataProvider setsCorrectSettingsProvider
     *
     * @param array $expectedSettings
     * @param string $indexState
     * @param array $settings
     *
     * @return void
     */
    public function testSetsCorrectSettings(array $expectedSettings, string $indexState, array $settings): void
    {
        $clientMock = $this->createClientMock();
        /** @var \Elastica\Index\|\PHPUnit\Framework\MockObject\MockObject $index */
        $index = $clientMock->getIndex('index');
        $index->expects($this->once())->method('setSettings')->with($expectedSettings);
        $indexDefinitionTransfer = new IndexDefinitionTransfer();
        $indexDefinitionTransfer->setSettings($settings);
        $indexSettingsUpdater = $this->createIndexSettingsUpdated($clientMock);
        $indexSettingsUpdater->method('getIndexState')->willReturn($indexState);

        $indexSettingsUpdater->run($indexDefinitionTransfer, new NullLogger());
    }

    /**
     * @return array
     */
    public function setsCorrectSettingsProvider(): array
    {
        return [
            'static settings' => [
                [
                    'index' => [
                        'number_of_replicas' => 1,
                        'refresh_interval' => 0,
                    ],
                ],
                SearchElasticsearchConfig::INDEX_OPEN_STATE,
                [
                    'index' => [
                        'number_of_shards' => 1,
                        'shard' => [
                            'check_on_startup' => true,
                        ],
                        'number_of_replicas' => 1,
                        'refresh_interval' => 0,
                    ],
                ],
            ],
            'dynamic settings' => [
                [
                    'index' => [
                        'shard' => [
                            'check_on_startup' => false,
                        ],
                    ],
                ],
                SearchElasticsearchConfig::INDEX_CLOSE_STATE,
                [
                    'index' => [
                        'number_of_shards' => 1,
                        'highlight' => [
                            'max_analyzed_offset' => 1,
                        ],
                        'shard' => [
                            'check_on_startup' => false,
                        ],
                        'number_of_replicas' => 1,
                        'refresh_interval' => 0,
                    ],
                ],
            ],
            'blacklisted' => [
                [
                    'index' => [
                        'number_of_replicas' => 1,
                    ],
                ],
                SearchElasticsearchConfig::INDEX_OPEN_STATE,
                [
                    'index' => [
                        'number_of_replicas' => 1,
                        'number_of_shards' => 1,
                        'routing_partition_size' => 1,
                    ],
                ],
            ],

        ];
    }

    /**
     * @return \Elastica\Client|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createClientMock(): Client
    {
        $clientMock = $this->createMock(Client::class);
        $clientMock->method('getIndex')
            ->willReturn(
                $this->createMock(Index::class)
            );

        return $clientMock;
    }

    /**
     * @param \Elastica\Client $client
     *
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Update\IndexSettingsUpdater|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createIndexSettingsUpdated(Client $client): IndexSettingsUpdater
    {
        return $this->getMockBuilder(IndexSettingsUpdater::class)
            ->setConstructorArgs([$client, $this->tester->getModuleConfig()])
            ->setMethods(['getIndexState'])
            ->getMock();
    }
}
