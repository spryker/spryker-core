<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Search\Business\Model\Elastisearch\Definition;

use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\JsonIndexDefinitionLoader;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\JsonIndexDefinitionMerger;

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
    public function testSingleIndexDefinitionLoadingWithMultipleMappingTypes()
    {
        $jsonIndexDefinitionLoader = new JsonIndexDefinitionLoader(
            [__DIR__ . '/Fixtures/SingleIndex'],
            $this->createJsonIndexDefinitionMerger(),
            $this->getStores()
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
            $this->getStores()
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
            $this->getStores()
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
            $this->getStores()
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
            $this->getStores()
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
            $stores
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
            $stores
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

}
