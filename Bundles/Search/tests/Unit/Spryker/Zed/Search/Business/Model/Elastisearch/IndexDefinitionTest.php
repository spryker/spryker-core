<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Search\Business\Model\Elastisearch;
use Spryker\Zed\Search\Business\Exception\InvalidMappingPropertyFormatException;
use Spryker\Zed\Search\Business\Exception\InvalidMappingTypeFormatException;
use Spryker\Zed\Search\Business\Model\Elasticsearch\IndexDefinition;

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
            IndexDefinition::SETTINGS => 'foo',
        ]);

        $this->assertEquals('foo', $indexDefinition->getSettings());
    }

    /**
     * @return void
     */
    public function testIndexDefinitionMappingTypes()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::MAPPING_TYPES => [
                IndexDefinition::MAPPING_TYPE => [],
            ],
        ]);

        $this->assertEquals([], $indexDefinition->getMappingTypes());
    }

    public function testValidMappingTypeShouldBeProcessedWithoutError()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::MAPPING_TYPES => [
                IndexDefinition::MAPPING_TYPE => [
                    IndexDefinition::NAME => 'type1',
                    IndexDefinition::MAPPING => [],
                ],
            ],
        ]);

        $expected = [
            [
                'name' => 'type1',
                'mapping' => [],
            ],
        ];
        $this->assertEquals($expected, $indexDefinition->getMappingTypes());
    }

    public function testValidMultipleMappingTypesShouldBeProcessedWithoutError()
    {
        $indexDefinition = new IndexDefinition([
            IndexDefinition::MAPPING_TYPES => [
                IndexDefinition::MAPPING_TYPE => [
                    [
                        IndexDefinition::NAME => 'type1',
                        IndexDefinition::MAPPING => [],
                    ],[
                        IndexDefinition::NAME => 'type2',
                        IndexDefinition::MAPPING => [],
                    ],
                ]
            ],
        ]);

        $expected = [
            [
                'name' => 'type1',
                'mapping' => [],
            ],
            [
                'name' => 'type2',
                'mapping' => [],
            ],
        ];
        $this->assertEquals($expected, $indexDefinition->getMappingTypes());
    }

    /**
     * @return void
     */
    public function testInvalidMappingTypeShouldThrowException()
    {
        $this->setExpectedException(InvalidMappingTypeFormatException::class);

        $indexDefinition = new IndexDefinition([
            IndexDefinition::MAPPING_TYPES => [
                IndexDefinition::MAPPING_TYPE => [
                    []
                ],
            ],
        ]);

        $indexDefinition->getMappingTypes();
    }

    /**
     * @return void
     */
    public function testInvalidMappingPropertiesShouldThrowException()
    {
        $this->setExpectedException(InvalidMappingPropertyFormatException::class);

        $indexDefinition = new IndexDefinition([
            IndexDefinition::MAPPING_TYPES => [
                IndexDefinition::MAPPING_TYPE => [
                    [
                        IndexDefinition::NAME => 'type1',
                        IndexDefinition::MAPPING => [
                            IndexDefinition::PROPERTIES => [
                                IndexDefinition::PROPERTY => [
                                    [],
                                ],
                            ],
                        ],
                    ],
                ]
            ],
        ]);

        $indexDefinition->getMappingTypes();
    }

}
