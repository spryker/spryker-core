<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Search\Business\Model\Elastisearch\Definition;

use Spryker\Zed\Search\Business\Exception\MissingNameAttributeException;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinition;

/**
 * @group Search
 * @group Business
 * @group Elasticsearch
 * @group IndexDefinition
 */
class IndexDefinitionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testIndexDefinitionName()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::NAME => 'foo',
        ]);

        $this->assertEquals('foo', $indexDefinition->getIndexName());
    }

    /**
     * @return void
     */
    public function testIndexDefinitionSettings()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::SETTINGS => [
                'foo',
                'bar' => 'Bar',
            ],
        ]);

        $expected = [
            'foo',
            'bar' => 'Bar',
        ];

        $this->assertEquals($expected, $indexDefinition->getSettings());
    }

    /**
     * @return void
     */
    public function testValidSingleMappingTypeShouldBeProcessedWithoutError()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::MAPPING => [
                IndexDefinition::NAME => 'type1',
                IndexDefinition::PROPERTY => [],
            ],
        ]);

        $expected = [
            'type1' => [],
        ];
        $this->assertEquals($expected, $indexDefinition->getMappings());
    }

    /**
     * @return void
     */
    public function testValidMultipleMappingTypesShouldBeProcessedWithoutError()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::MAPPING => [
                [
                    IndexDefinition::NAME => 'type1',
                    IndexDefinition::PROPERTY => [],
                ],[
                    IndexDefinition::NAME => 'type2',
                    IndexDefinition::PROPERTY => [],
                ],
            ],
        ]);

        $expected = [
            'type1' => [],
            'type2' => [],
        ];
        $this->assertEquals($expected, $indexDefinition->getMappings());
    }

    /**
     * @return void
     */
    public function testInvalidMappingTypeShouldThrowException()
    {
        $this->setExpectedException(MissingNameAttributeException::class);

        $indexDefinition = new IndexDefinition([
            IndexDefinition::MAPPING => [
                []
            ],
        ]);

        $indexDefinition->getMappings();
    }

    /**
     * @return void
     */
    public function testInvalidMappingPropertiesShouldThrowException()
    {
        $this->setExpectedException(MissingNameAttributeException::class);

        $indexDefinition = new IndexDefinition([
            IndexDefinition::MAPPING => [
                IndexDefinition::NAME => 'type1',
                IndexDefinition::PROPERTY => [
                    [],
                ],
            ],
        ]);

        $indexDefinition->getMappings();
    }

    /**
     * @return void
     */
    public function testValidSinglePropertyDefinitionShouldBeProcessed()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::MAPPING => [
                IndexDefinition::NAME => 'type1',
                IndexDefinition::PROPERTY => [
                    IndexDefinition::NAME => 'property1',
                    'foo' => 'Foo',
                ],
            ],
        ]);

        $expected = [
            'property1' => ['foo' => 'Foo'],
        ];

        $this->assertEquals($expected, $indexDefinition->getMappings()['type1']);
    }

    /**
     * @return void
     */
    public function testValidMultiplePropertyDefinitionShouldBeProcessed()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::MAPPING => [
                IndexDefinition::NAME => 'type1',
                IndexDefinition::PROPERTY => [
                    [
                        IndexDefinition::NAME => 'property1',
                        'foo' => 'Foo',
                    ],
                    [
                        IndexDefinition::NAME => 'property2',
                        'bar' => 'Bar',
                    ],
                    [
                        IndexDefinition::NAME => 'property3',
                        'baz' => 'Baz',
                    ],
                ],
            ],
        ]);

        $expected = [
            'property1' => ['foo' => 'Foo'],
            'property2' => ['bar' => 'Bar'],
            'property3' => ['baz' => 'Baz'],
        ];

        $this->assertEquals($expected, $indexDefinition->getMappings()['type1']);
    }

    /**
     * @return void
     */
    public function testValidRecursivePropertyDefinitionShouldBeProcessed()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::MAPPING => [
            IndexDefinition::NAME => 'type1',
                IndexDefinition::PROPERTY => [
                    [
                        IndexDefinition::NAME => 'property1',
                        'foo' => 'Foo',
                        IndexDefinition::PROPERTIES => [
                            IndexDefinition::PROPERTY => [
                                [
                                    IndexDefinition::NAME => 'property1.1',
                                ],
                                [
                                    IndexDefinition::NAME => 'property1.2',
                                ],
                                [
                                    IndexDefinition::NAME => 'property1.3',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $expected = [
            'property1' => [
                'foo' => 'Foo',
                'properties' => [
                    'property1.1' => [],
                    'property1.2' => [],
                    'property1.3' => [],
                ],
            ],
        ];

        $this->assertEquals($expected, $indexDefinition->getMappings()['type1']);
    }

    /**
     * @return void
     */
    public function testValidSingleAnalyzerDefinitionShouldBeProcessed()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::SETTINGS => [
                IndexDefinition::ANALYSIS => [
                    IndexDefinition::ANALYZER => [
                        IndexDefinition::NAME => 'analyzer1',
                        'foo' => 'Foo',
                    ],
                ],
            ],
        ]);

        $expected = [
            'analyzer1' => ['foo' => 'Foo'],
        ];

        $this->assertEquals($expected, $indexDefinition->getSettings()[IndexDefinition::ANALYSIS][IndexDefinition::ANALYZER]);
    }

    /**
     * @return void
     */
    public function testValidMultipleAnalyzerDefinitionShouldBeProcessed()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::SETTINGS => [
                IndexDefinition::ANALYSIS => [
                    IndexDefinition::ANALYZER => [
                        [
                            IndexDefinition::NAME => 'analyzer1',
                            'foo' => 'Foo',
                        ],
                        [
                            IndexDefinition::NAME => 'analyzer2',
                            'bar' => 'Bar',
                        ],
                    ],
                ],
            ],
        ]);

        $expected = [
            'analyzer1' => ['foo' => 'Foo'],
            'analyzer2' => ['bar' => 'Bar'],
        ];

        $this->assertEquals($expected, $indexDefinition->getSettings()[IndexDefinition::ANALYSIS][IndexDefinition::ANALYZER]);
    }

    /**
     * @return void
     */
    public function testValidComplexAnalyzerDefinitionShouldBeProcessed()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::SETTINGS => [
                IndexDefinition::ANALYSIS => [
                    IndexDefinition::ANALYZER => [
                        [
                            IndexDefinition::NAME => 'analyzer1',
                            'foo' => 'Foo',
                            'filter' => ['lowercase', 'mySnowball']
                        ],
                    ],
                ],
            ],
        ]);

        $expected = [
            'analyzer1' => [
                'foo' => 'Foo',
                'filter' => ['lowercase', 'mySnowball']
            ],
        ];

        $this->assertEquals($expected, $indexDefinition->getSettings()[IndexDefinition::ANALYSIS][IndexDefinition::ANALYZER]);
    }

    /**
     * @return void
     */
    public function testValidCharFilterDefinitionShouldBeProcessed()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::SETTINGS => [
                IndexDefinition::ANALYSIS => [
                    IndexDefinition::CHAR_FILTER => [
                        IndexDefinition::NAME => 'char_filter1',
                        'foo' => 'Foo',
                    ],
                ],
            ],
        ]);

        $expected = [
            'char_filter1' => ['foo' => 'Foo'],
        ];

        $this->assertEquals($expected, $indexDefinition->getSettings()[IndexDefinition::ANALYSIS][IndexDefinition::CHAR_FILTER]);
    }

    /**
     * @return void
     */
    public function testValidFilterDefinitionShouldByProcessed()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::SETTINGS => [
                IndexDefinition::ANALYSIS => [
                    IndexDefinition::FILTER => [
                        IndexDefinition::NAME => 'filter1',
                        'foo' => 'Foo',
                    ],
                ],
            ],
        ]);

        $expected = [
            'filter1' => ['foo' => 'Foo'],
        ];

        $this->assertEquals($expected, $indexDefinition->getSettings()[IndexDefinition::ANALYSIS][IndexDefinition::FILTER]);
    }

    /**
     * @return void
     */
    public function testValidTokenizerDefinitionShouldBeProcessed()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::SETTINGS => [
                IndexDefinition::ANALYSIS => [
                    IndexDefinition::TOKENIZER => [
                        IndexDefinition::NAME => 'tokenizer1',
                        'foo' => 'Foo',
                    ],
                ],
            ],
        ]);

        $expected = [
            'tokenizer1' => ['foo' => 'Foo'],
        ];

        $this->assertEquals($expected, $indexDefinition->getSettings()[IndexDefinition::ANALYSIS][IndexDefinition::TOKENIZER]);
    }

}
