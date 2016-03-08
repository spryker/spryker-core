<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Search\Business\Model\Elastisearch\Definition;

use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\XmlIndexDefinitionLoader;
use Symfony\Component\Finder\Finder;

/**
 * @group Search
 * @group Business
 * @group Elasticsearch
 * @group XmlIndexDefinitionLoader
 */
class XmlIndexDefinitionLoaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testSingleIndexDefinitionLoadingWithMultipleMappingTypes()
    {
        $xmlIndexDefinitionLoader = new XmlIndexDefinitionLoader([__DIR__ . '/Fixtures/SingleIndex']);

        $definitions = $xmlIndexDefinitionLoader->loadIndexDefinitions();

        $this->assertEquals('foo', $definitions[0]->getIndexName());
    }

    /**
     * @return void
     */
    public function testSingleIndexDefinitionSettings()
    {
        $xmlIndexDefinitionLoader = new XmlIndexDefinitionLoader([__DIR__ . '/Fixtures/SingleIndex']);

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
                        ]
                    ],
                ]
            ],
        ];

        $definitions = $xmlIndexDefinitionLoader->loadIndexDefinitions();

        $this->assertEquals($expectedSettings, $definitions[0]->getSettings());
    }

    /**
     * @return void
     */
    public function testSingleIndexDefinitionMappings()
    {
        $xmlIndexDefinitionLoader = new XmlIndexDefinitionLoader([__DIR__ . '/Fixtures/SingleIndex']);

        $expectedMappings = [
            'page1' => [
                'foo' => [
                    'analyzer' => 'my_analyzer',
                ]
            ],
            'page2' => [],
        ];

        $definitions = $xmlIndexDefinitionLoader->loadIndexDefinitions();

        $this->assertEquals($expectedMappings, $definitions[0]->getMappings());
    }

    /**
     * @return void
     */
    public function testMultipleIndexDefinitionLoading()
    {
        $xmlIndexDefinitionLoader = new XmlIndexDefinitionLoader([__DIR__ . '/Fixtures/MultipleIndex']);

        $definitions = $xmlIndexDefinitionLoader->loadIndexDefinitions();

        $this->assertEquals(3, count($definitions));
        $this->assertEquals('foo', $definitions[0]->getIndexName(), 'Name of IndexDefinition #0 should be foo');
        $this->assertEquals('bar', $definitions[1]->getIndexName(), 'Name of IndexDefinition #1 should be bar');
        $this->assertEquals('baz', $definitions[2]->getIndexName(), 'Name of IndexDefinition #2 should be baz');
    }

}
