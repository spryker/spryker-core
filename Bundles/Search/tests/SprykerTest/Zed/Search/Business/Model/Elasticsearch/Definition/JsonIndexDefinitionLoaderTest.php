<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business\Model\Elasticsearch\Definition;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Search\Business\Definition\JsonIndexDefinitionFinder;
use Spryker\Zed\Search\Business\Definition\JsonIndexDefinitionMapper;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\JsonIndexDefinitionLoader;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\JsonIndexDefinitionMerger;
use Spryker\Zed\Search\Dependency\Facade\SearchToStoreFacadeBridge;
use Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Model
 * @group Elasticsearch
 * @group Definition
 * @group JsonIndexDefinitionLoaderTest
 * Add your own group annotations below this line
 */
class JsonIndexDefinitionLoaderTest extends Unit
{
    /**
     * @return void
     */
    public function testEmptyIndexDefinitionLoading(): void
    {
        // Arrange
        $jsonIndexDefinitionLoader = $this->createJsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/EmptyIndex'],
            $this->createJsonIndexDefinitionMerger(),
            ['DE'],
            'DE'
        );

        // Act
        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        // Assert
        $this->assertEmpty($definitions['de_foo']->getSettings(), 'empty foo settings');
        $this->assertEmpty($definitions['de_foo']->getMappings(), 'empty foo mapping');
        $this->assertEmpty($definitions['de_bar']->getSettings(), 'empty bar settings');
        $this->assertNotEmpty($definitions['de_bar']->getMappings(), 'not empty bar mapping');
        $this->assertNotEmpty($definitions['de_baz']->getSettings(), 'not empty baz settings');
        $this->assertEmpty($definitions['de_baz']->getMappings(), 'empty baz mapping');
    }

    /**
     * @return void
     */
    public function testSingleIndexDefinitionLoadingWithMultipleMappingTypes(): void
    {
        // Arrange
        $jsonIndexDefinitionLoader = $this->createJsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/SingleIndex'],
            $this->createJsonIndexDefinitionMerger(),
            ['DE'],
            'DE'
        );

        // Act
        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        // Assert
        $this->assertSame('de_foo', $definitions['de_foo']->getIndexName());
    }

    /**
     * @return void
     */
    public function testSingleIndexDefinitionSettings(): void
    {
        // Arrange
        $jsonIndexDefinitionLoader = $this->createJsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/SingleIndex'],
            $this->createJsonIndexDefinitionMerger(),
            ['DE'],
            'DE'
        );

        $expectedSettings = [
            'number_of_shards' => '1',
            'number_of_replicas' => '1',
            'analysis' => [
                'analyzer' => [
                    'my_analyzer' => [
                        'type' => 'standard',
                        'stopwords' => '_german_',
                        'filter' => [
                            'standard',
                            'lowercase',
                        ],
                    ],
                ],
            ],
        ];

        // Act
        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        // Assert
        $this->assertEquals($expectedSettings, $definitions['de_foo']->getSettings());
    }

    /**
     * @return void
     */
    public function testSingleIndexDefinitionMappings(): void
    {
        // Arrange
        $jsonIndexDefinitionLoader = $this->createJsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/SingleIndex'],
            $this->createJsonIndexDefinitionMerger(),
            ['DE'],
            'DE'
        );

        $expectedMappings = [
            'page1' => [
                'properties' => [
                    'foo' => [
                        'analyzer' => 'my_analyzer',
                    ],
                    'bar' => [
                        'properties' => [
                            'baz' => [],
                        ],
                    ],
                ],
            ],
        ];

        // Act
        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        // Assert
        $this->assertEquals($expectedMappings, $definitions['de_foo']->getMappings());
        $this->assertCount(1, $definitions);
    }

    /**
     * @return void
     */
    public function testIsIndexNameSuffixUsedWhenProvided(): void
    {
        // Arrange
        $suffix = '_suffix';

        $jsonIndexDefinitionLoader = $this->createJsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/SingleIndex'],
            $this->createJsonIndexDefinitionMerger(),
            ['DE'],
            'DE',
            $suffix
        );

        $expectedMappings = [
            'page1' => [
                'properties' => [
                    'foo' => [
                        'analyzer' => 'my_analyzer',
                    ],
                    'bar' => [
                        'properties' => [
                            'baz' => [],
                        ],
                    ],
                ],
            ],
        ];

        // Act
        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        // Assert
        $this->assertEquals($expectedMappings, $definitions['de_foo' . $suffix]->getMappings());
    }

    /**
     * @return void
     */
    public function testMultipleIndexDefinitionLoading(): void
    {
        // Arrange
        $jsonIndexDefinitionLoader = $this->createJsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/MultipleIndex'],
            $this->createJsonIndexDefinitionMerger(),
            ['DE'],
            'DE'
        );

        // Act
        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        // Assert
        $this->assertSame(3, count($definitions));
    }

    /**
     * @return void
     */
    public function testMultipleIndexDefinitionMergingWithStoreSpecificFile(): void
    {
        // Arrange
        $fooExpectedDefinition = (new ElasticsearchIndexDefinitionTransfer())
            ->setIndexName('de_foo')
            ->setSettings([
                'index' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2,
                ],
            ])
            ->setMappings([
                'page' => [
                    'properties' => [
                        'foo' => [
                            'type' => 'integer',
                        ],
                        'bar' => [
                            'type' => 'string',
                        ],
                        'baz' => [
                            'type' => 'string',
                        ],
                    ],
                ],
                'page2' => [],
            ]);

        $barExpectedDefinition = (new ElasticsearchIndexDefinitionTransfer())
            ->setIndexName('de_bar')
            ->setSettings([])
            ->setMappings([]);

        $jsonIndexDefinitionLoader = $this->createJsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/Merge/*/'],
            $this->createJsonIndexDefinitionMerger(),
            ['DE'],
            'DE'
        );

        // Act
        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        // Assert
        $this->assertEquals($fooExpectedDefinition, $definitions['de_foo']);
        $this->assertEquals($barExpectedDefinition, $definitions['de_bar']);
    }

    /**
     * @return void
     */
    public function testDefinitionsShouldBeCreatedOnlyForCurrentStore(): void
    {
        // Arrange
        $jsonIndexDefinitionLoader = $this->createJsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/Stores/Core/'],
            $this->createJsonIndexDefinitionMerger(),
            ['A', 'B', 'C'],
            'A'
        );

        // Act
        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        // Assert
        $this->assertArrayHasKey('a_foo', $definitions);
        $this->assertArrayNotHasKey('b_foo', $definitions);
        $this->assertArrayNotHasKey('c_foo', $definitions);
    }

    /**
     * @return void
     */
    public function testStoreDefinitionShouldBeOverwritableFromProject(): void
    {
        // Arrange
        $jsonIndexDefinitionLoader = $this->createJsonIndexDefinitionLoader(
            [
                __DIR__ . '/Fixtures/Stores/Core/',
                __DIR__ . '/Fixtures/Stores/Project/',
            ],
            $this->createJsonIndexDefinitionMerger(),
            ['A', 'B'],
            'A'
        );

        $expectedDefinition = (new ElasticsearchIndexDefinitionTransfer())
            ->setIndexName('a_foo')
            ->setSettings([
                'number_of_shards' => 10,
                'number_of_replicas' => 1,
            ])
            ->setMappings([
                'page' => [
                    'properties' => [
                        'foo' => [
                            'type' => 'string',
                        ],
                        'bar' => [],
                    ],
                ],
            ]);

        // Act
        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        // Assert
        $this->assertEquals($expectedDefinition, $definitions['a_foo']);
    }

    /**
     * @param array $sourceDirectories
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\JsonIndexDefinitionMerger $definitionMerger
     * @param string[] $storeNames
     * @param string $currentStoreName
     * @param string $suffix
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\JsonIndexDefinitionLoader
     */
    protected function createJsonIndexDefinitionLoader(
        array $sourceDirectories,
        JsonIndexDefinitionMerger $definitionMerger,
        array $storeNames,
        string $currentStoreName,
        string $suffix = ''
    ): JsonIndexDefinitionLoader {
        $storeFacadeBridgeMock = $this->getStoreFacadeBridgeMock($currentStoreName, $storeNames);
        $jsonIndexDefinitionFinder = $this->getJsonIndexDefinitionFinder($sourceDirectories, $storeFacadeBridgeMock);

        $jsonIndexDefinitionLoader = $this->getMockBuilder(JsonIndexDefinitionLoader::class)
            ->setConstructorArgs([$jsonIndexDefinitionFinder, $definitionMerger, $storeFacadeBridgeMock])
            ->onlyMethods(['getIndexNameSuffix'])
            ->getMock();

        $jsonIndexDefinitionLoader->method('getIndexNameSuffix')
            ->willReturn($suffix);

        return $jsonIndexDefinitionLoader;
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\JsonIndexDefinitionMerger
     */
    protected function createJsonIndexDefinitionMerger(): JsonIndexDefinitionMerger
    {
        return new JsonIndexDefinitionMerger();
    }

    /**
     * @param string $currentStoreName
     * @param string[] $storeNames
     *
     * @return \Spryker\Zed\Search\Dependency\Facade\SearchToStoreFacadeBridge|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getStoreFacadeBridgeMock(string $currentStoreName, array $storeNames): SearchToStoreFacadeBridge
    {
        $currentStoreTransfer = (new StoreTransfer())->setName($currentStoreName);
        $storeTransfers = [];
        foreach ($storeNames as $storeName) {
            $storeTransfers[] = (new StoreTransfer())->setName($storeName);
        }

        $searchToStoreFacadeBridgeMock = $this->getMockBuilder(SearchToStoreFacadeBridge::class)
            ->onlyMethods(['getCurrentStore', 'getAllStores'])
            ->disableOriginalConstructor()
            ->getMock();

        $searchToStoreFacadeBridgeMock->method('getCurrentStore')->willReturn($currentStoreTransfer);
        $searchToStoreFacadeBridgeMock->method('getAllStores')->willReturn($storeTransfers);

        return $searchToStoreFacadeBridgeMock;
    }

    /**
     * @param string[] $sourceDirectories
     * @param \Spryker\Zed\Search\Dependency\Facade\SearchToStoreFacadeBridge $searchToStoreFacadeBridge
     *
     * @return \Spryker\Zed\Search\Business\Definition\JsonIndexDefinitionFinder
     */
    protected function getJsonIndexDefinitionFinder(
        array $sourceDirectories,
        SearchToStoreFacadeBridge $searchToStoreFacadeBridge
    ): JsonIndexDefinitionFinder {
        return new JsonIndexDefinitionFinder(
            $sourceDirectories,
            $this->getJsonIndexDefinitionMapper($searchToStoreFacadeBridge)
        );
    }

    /**
     * @param \Spryker\Zed\Search\Dependency\Facade\SearchToStoreFacadeBridge $searchToStoreFacadeBridge
     *
     * @return \Spryker\Zed\Search\Business\Definition\JsonIndexDefinitionMapper
     */
    protected function getJsonIndexDefinitionMapper(SearchToStoreFacadeBridge $searchToStoreFacadeBridge): JsonIndexDefinitionMapper
    {
        return new JsonIndexDefinitionMapper(
            $this->getUtilEncodingMock(),
            $searchToStoreFacadeBridge
        );
    }

    /**
     * @return array
     */
    protected function getStores(): array
    {
        return ['DE'];
    }

    /**
     * @return \Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getUtilEncodingMock(): SearchToUtilEncodingInterface
    {
        $utilEncodingMock = $this->getMockBuilder(SearchToUtilEncodingInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['decodeJson'])
            ->getMock();

        $utilEncodingMock
            ->method('decodeJson')
            ->willReturnCallback(function ($json, $assoc) {
                return json_decode($json, $assoc);
            });

        return $utilEncodingMock;
    }
}
