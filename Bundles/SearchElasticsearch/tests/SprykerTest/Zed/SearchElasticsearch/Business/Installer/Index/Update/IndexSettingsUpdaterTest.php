<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @var \Elastica\Client|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $clientMock;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Update\IndexSettingsUpdater|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $indexSettingsUpdater;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $this->clientMock = $this->createClientMock();
        $this->indexSettingsUpdater = $this->createIndexSettingsUpdater();
    }

    /**
     * @dataProvider canAcceptIndexDefinitionProvider
     *
     * @param bool $expectedResult
     * @param bool $indexExists
     * @param array $settings
     *
     * @return void
     */
    public function testCanByAppliedForIndex(bool $expectedResult, bool $indexExists, array $settings): void
    {
        // Arrange
        /** @var \Elastica\Index\|\PHPUnit\Framework\MockObject\MockObject $index */
        $index = $this->clientMock->getIndex('index');
        $index->method('exists')->willReturn($indexExists);
        $indexDefinitionTransfer = $this->createIndexDefinitionTransfer($settings);

        // Act
        $result = $this->indexSettingsUpdater->accept($indexDefinitionTransfer);

        // Assert
        $this->assertSame($expectedResult, $result);
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
        /** @var \Elastica\Index\|\PHPUnit\Framework\MockObject\MockObject $indexMock */
        $indexMock = $this->clientMock->getIndex('index');
        $indexMock->expects($this->once())->method('setSettings')->with($expectedSettings);
        $indexDefinitionTransfer = $this->createIndexDefinitionTransfer($settings);
        $this->indexSettingsUpdater->method('getIndexState')->willReturn($indexState);

        $this->indexSettingsUpdater->run($indexDefinitionTransfer, new NullLogger());
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
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Update\IndexSettingsUpdater|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createIndexSettingsUpdater(): IndexSettingsUpdater
    {
        return $this->getMockBuilder(IndexSettingsUpdater::class)
            ->setConstructorArgs([
                $this->clientMock,
                $this->tester->getModuleConfig(),
                $this->tester->getFactory()->getUtilSanitizeService(),
            ])
            ->setMethods(['getIndexState'])
            ->getMock();
    }

    /**
     * @param array $settings
     * @param array $mappings
     * @param string $indexName
     *
     * @return \Generated\Shared\Transfer\IndexDefinitionTransfer
     */
    protected function createIndexDefinitionTransfer(array $settings, array $mappings = [], string $indexName = ''): IndexDefinitionTransfer
    {
        return (new IndexDefinitionTransfer())->setSettings($settings)
            ->setMappings($mappings)
            ->setIndexName($indexName);
    }
}
