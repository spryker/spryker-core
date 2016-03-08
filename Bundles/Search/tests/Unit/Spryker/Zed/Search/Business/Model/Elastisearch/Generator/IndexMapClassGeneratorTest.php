<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Search\Business\Model\Generator;

use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinition;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGenerator;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleaner;

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

        $indexDefinition = new IndexDefinition([
            IndexDefinition::NAME => 'index1',
            IndexDefinition::MAPPING => [
                IndexDefinition::NAME => 'simple',
                IndexDefinition::PROPERTY => [
                    [
                        IndexDefinition::NAME => 'foo',
                        'a' => 'asdf',
                        'b' => 'qwer',
                    ],
                    [
                        IndexDefinition::NAME => 'bar',
                        'a' => 'asdf',
                        'b' => 'qwer',
                    ],
                    [
                        IndexDefinition::NAME => 'baz',
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

        $indexDefinition = new IndexDefinition([
            IndexDefinition::NAME => 'index-1',
            IndexDefinition::MAPPING => [
                IndexDefinition::NAME => 'complex',
                IndexDefinition::PROPERTY => [
                    [
                        IndexDefinition::NAME => 'foo',
                        'a' => 'asdf',
                        'b' => 'qwer',
                        IndexDefinition::PROPERTIES => [
                            IndexDefinition::PROPERTY => [
                                [
                                    IndexDefinition::NAME => 'bar',
                                    'a' => 'asdf',
                                    'b' => 'qwer',
                                    IndexDefinition::PROPERTIES => [
                                        IndexDefinition::PROPERTY => [
                                            [
                                                IndexDefinition::NAME => 'baz',
                                                'a' => 'asdf',
                                                'b' => 'qwer',
                                            ],
                                        ],
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

}
