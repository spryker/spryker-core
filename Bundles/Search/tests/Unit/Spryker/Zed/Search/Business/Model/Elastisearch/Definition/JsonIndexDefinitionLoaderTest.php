<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Search\Business\Model\Elastisearch\Definition;

use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\JsonIndexDefinitionLoader;

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
        $jsonIndexDefinitionLoader = new JsonIndexDefinitionLoader([__DIR__ . '/Fixtures/SingleIndex']);

        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        $this->assertEquals('foo', $definitions[0]->getIndexName());
    }

    /**
     * @return void
     */
    public function testSingleIndexDefinitionSettings()
    {
        $jsonIndexDefinitionLoader = new JsonIndexDefinitionLoader([__DIR__ . '/Fixtures/SingleIndex']);

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

        $this->assertEquals($expectedSettings, $definitions[0]->getSettings());
    }

    /**
     * @return void
     */
    public function testSingleIndexDefinitionMappings()
    {
        $jsonIndexDefinitionLoader = new JsonIndexDefinitionLoader([__DIR__ . '/Fixtures/SingleIndex']);

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

        $this->assertEquals($expectedMappings, $definitions[0]->getMappings());
    }

    /**
     * @return void
     */
    public function testMultipleIndexDefinitionLoading()
    {
        $jsonIndexDefinitionLoader = new JsonIndexDefinitionLoader([__DIR__ . '/Fixtures/MultipleIndex']);

        $definitions = $jsonIndexDefinitionLoader->loadIndexDefinitions();

        $this->assertEquals(3, count($definitions));
    }

}
