<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Search\Business\Model\Elasticsearch\Definition;

use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\JsonIndexDefinitionLoader;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\JsonIndexDefinitionMerger;
use Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface;

/**
 * @group Search
 * @group Business
 * @group Elasticsearch
 * @group JsonIndexDefinitionLoader
 */
class JsonIndexDefinitionLoaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testEmptyIndexDefinitionLoading()
    {
        $jsonIndexDefinitionLoader = new JsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/EmptyIndex'],
            $this->createJsonIndexDefinitionMerger(),
            $this->getStores(),
            $this->getUtilEncodingMock()
        );

        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

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
    public function testSingleIndexDefinitionLoadingWithMultipleMappingTypes()
    {
        $jsonIndexDefinitionLoader = new JsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/SingleIndex'],
            $this->createJsonIndexDefinitionMerger(),
            $this->getStores(),
            $this->getUtilEncodingMock()
        );

        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        $this->assertEquals('de_foo', $definitions['de_foo']->getIndexName());
    }

    /**
     * @return void
     */
    public function testSingleIndexDefinitionSettings()
    {
        $jsonIndexDefinitionLoader = new JsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/SingleIndex'],
            $this->createJsonIndexDefinitionMerger(),
            $this->getStores(),
            $this->getUtilEncodingMock()
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

        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        $this->assertEquals($expectedSettings, $definitions['de_foo']->getSettings());
    }

    /**
     * @return void
     */
    public function testSingleIndexDefinitionMappings()
    {
        $jsonIndexDefinitionLoader = new JsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/SingleIndex'],
            $this->createJsonIndexDefinitionMerger(),
            $this->getStores(),
            $this->getUtilEncodingMock()
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

        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        $this->assertEquals($expectedMappings, $definitions['de_foo']->getMappings());
    }

    /**
     * @return void
     */
    public function testMultipleIndexDefinitionLoading()
    {
        $jsonIndexDefinitionLoader = new JsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/MultipleIndex'],
            $this->createJsonIndexDefinitionMerger(),
            $this->getStores(),
            $this->getUtilEncodingMock()
        );

        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        $this->assertEquals(3, count($definitions));
    }

    /**
     * @return void
     */
    public function testMultipleIndexDefinitionMerging()
    {
        $fooExpectedDefinition = (new ElasticsearchIndexDefinitionTransfer())
            ->setIndexName('de_foo')
            ->setSettings([
                'index' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 4,
                ]
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

        $jsonIndexDefinitionLoader = new JsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/Merge/*/'],
            $this->createJsonIndexDefinitionMerger(),
            $this->getStores(),
            $this->getUtilEncodingMock()
        );

        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        $this->assertEquals($fooExpectedDefinition, $definitions['de_foo']);
        $this->assertEquals($barExpectedDefinition, $definitions['de_bar']);
    }

    /**
     * @return void
     */
    public function testDefinitionsShouldBeCreatedPerStore()
    {
        $stores = ['A', 'B', 'C'];

        $jsonIndexDefinitionLoader = new JsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/Stores/Core/'],
            $this->createJsonIndexDefinitionMerger(),
            $stores,
            $this->getUtilEncodingMock()
        );

        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        $this->assertArrayHasKey('a_foo', $definitions);
        $this->assertArrayHasKey('b_foo', $definitions);
        $this->assertArrayHasKey('c_foo', $definitions);
    }

    /**
     * @return void
     */
    public function testStoreDefinitionShouldBeOverwritable()
    {
        $stores = ['A', 'B'];

        $jsonIndexDefinitionLoader = new JsonIndexDefinitionLoader(
            [
                __DIR__ . '/Fixtures/Stores/Core/',
                __DIR__ . '/Fixtures/Stores/Project/',
            ],
            $this->createJsonIndexDefinitionMerger(),
            $stores,
            $this->getUtilEncodingMock()
        );

        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        $store1ExpectedDefinition = (new ElasticsearchIndexDefinitionTransfer())
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

        $store2ExpectedDefinition = (new ElasticsearchIndexDefinitionTransfer())
            ->setIndexName('b_foo')
            ->setSettings([
                'number_of_shards' => 1,
                'number_of_replicas' => 1,
            ])
            ->setMappings([
                'page' => [
                    'properties' => [
                        'foo' => [
                            'type' => 'integer',
                        ],
                        'bar' => [],
                    ],
                ],
            ]);

        $this->assertEquals($store1ExpectedDefinition, $definitions['a_foo']);
        $this->assertEquals($store2ExpectedDefinition, $definitions['b_foo']);
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionMergerInterface
     */
    protected function createJsonIndexDefinitionMerger()
    {
        return new JsonIndexDefinitionMerger();
    }

    /**
     * @return array
     */
    protected function getStores()
    {
        return ['DE'];
    }

    /**
     * @return \Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getUtilEncodingMock()
    {
        $utilEncodingMock = $this->getMockBuilder(SearchToUtilEncodingInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['decodeJson'])
            ->getMock();

        $utilEncodingMock
            ->method('decodeJson')
            ->willReturnCallback(function($json, $assoc){
                return json_decode($json, $assoc);
            });

        return $utilEncodingMock;
    }

}
