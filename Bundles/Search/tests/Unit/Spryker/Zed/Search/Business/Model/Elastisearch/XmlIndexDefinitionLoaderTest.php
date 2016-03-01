<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Search\Business\Model\Elastisearch;

use Spryker\Zed\Search\Business\Model\Elasticsearch\XmlIndexDefinitionLoader;
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
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\XmlIndexDefinitionLoader::__construct
     */
    protected $xmlIndexDefinitionLoader;

    public function setUp()
    {
        $this->xmlIndexDefinitionLoader = new XmlIndexDefinitionLoader([
            __DIR__ . '/Fixtures/',
        ]);
    }

    public function test()
    {
        $definitions = $this->xmlIndexDefinitionLoader->loadIndexDefinitions();

        $expected = [[
            'index' => [
                'name' => 'foo',
                'settings' => [
                    'number_of_shards' => '1',
                    'number_of_replicas' => '1',
//                    'analysis' => [],
                ],
                'mapping_types' => [
                    [
                        'name' => 'page',
                        'mapping' => [
                            [
                                'name' => 'store',
                                'type' => 'string',
                                'include_in_all' => 'false',
                            ],
                            [
                                'name' => 'search-result-data',
                                'type' => 'object',
                                'include_in_all' => 'false',
                                'properties' => [
                                    [
                                        'name' => 'sku',
                                        'type' => 'string',
                                        'index' => 'not_analyzed',
                                    ],
                                    [
                                        'name' => 'name',
                                        'type' => 'string',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]];

        $this->assertEquals($expected, $definitions);
    }

}
