<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Search\Business\Model\Generator;

use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleaner;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGenerator;

/**
 * @group Search
 * @group Business
 * @group IndexMapClassGenerator
 */
class IndexMapClassGeneratorTest extends \PHPUnit_Framework_TestCase
{

    const TARGET_DIRECTORY = __DIR__ . '/Generated/';
    const FIXTURES_DIRECTORY = __DIR__ . '/Fixtures/';

    /**
     * @return void
     */
    public function tearDown()
    {
        $searchMapCleaner = new IndexMapCleaner(self::TARGET_DIRECTORY);
        $searchMapCleaner->cleanDirectory();
    }

    /**
     * @return void
     */
    public function testGenerateSimpleIndexMap()
    {
        $generator = new IndexMapGenerator(self::TARGET_DIRECTORY);

        $indexDefinition = $this->createIndexDefinition('index1', [], [
            'simple' => [
                'properties' => [
                    'foo' => [
                        'a' => 'asdf',
                        'b' => 'qwer',
                    ],
                    'bar' => [
                        'a' => 'asdf',
                        'b' => 'qwer',
                    ],
                    'baz' => [
                        'a' => 'asdf',
                        'b' => 'qwer',
                    ],
                ],
            ],
        ]);

        $generator->generate($indexDefinition);

        $this->assertFileEquals(
            self::FIXTURES_DIRECTORY . 'SimpleIndexMap.expected.php',
            self::TARGET_DIRECTORY . 'Index1/SimpleIndexMap.php'
        );
    }

    /**
     * @return void
     */
    public function testGenerateComplexIndexMap()
    {
        $generator = new IndexMapGenerator(self::TARGET_DIRECTORY);

        $indexDefinition = $this->createIndexDefinition('index-1', [], [
            'complex' => [
                'properties' => [
                    'foo' => [
                        'a' => 'asdf',
                        'b' => 'qwer',
                        'properties' => [
                            'bar' => [
                                'a' => 'asdf',
                                'b' => 'qwer',
                                'properties' => [
                                    'baz' => [
                                        'a' => 'asdf',
                                        'b' => 'qwer',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $generator->generate($indexDefinition);

        $this->assertFileEquals(
            self::FIXTURES_DIRECTORY . 'ComplexIndexMap.expected.php',
            self::TARGET_DIRECTORY . 'Index1/ComplexIndexMap.php'
        );
    }

    /**
     * @param string $name
     * @param array $settings
     * @param array $mappings
     *
     * @return \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer
     */
    protected function createIndexDefinition($name, array $settings = [], array $mappings = [])
    {
        $indexDefinition = new ElasticsearchIndexDefinitionTransfer();
        $indexDefinition
            ->setIndexName($name)
            ->setSettings($settings)
            ->setMappings($mappings);

        return $indexDefinition;
    }

}
