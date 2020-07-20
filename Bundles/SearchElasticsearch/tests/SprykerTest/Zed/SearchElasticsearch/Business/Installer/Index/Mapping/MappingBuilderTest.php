<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\Installer\Index\Mapping;

use Codeception\Test\Unit;
use Elastica\Index;
use Elastica\Type;
use Generated\Shared\Transfer\IndexDefinitionTransfer;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilder;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingTypeAwareMappingBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Installer
 * @group Index
 * @group Mapping
 * @group MappingBuilderTest
 * Add your own group annotations below this line
 */
class MappingBuilderTest extends Unit
{
    /**
     * @var string[][]
     */
    protected $fixtureMappingData = [
        'dummy_mapping_type' => [
            'foo_key' => ['foo_value'],
            'bar_key' => ['bar_value'],
            'baz_key' => ['baz_value'],
        ],
    ];

    /**
     * @deprecated Will be removed after the support of mapping types is dropped.
     *
     * @return void
     */
    public function testCanBuildMappingWithType(): void
    {
        if (!class_exists('\Elastica\Query\Type')) {
            $this->markTestSkipped('This test can only be run in Elasticsearch 6 (or lower) environment.');
        }

        $mappingType = 'page';

        /** @var \Elastica\Index|\PHPUnit\Framework\MockObject\MockObject $indexMock */
        $indexMock = $this->createMock(Index::class);
        $indexMock->method('getType')->willReturn(new Type($indexMock, $mappingType));

        $mappingBuilder = new MappingTypeAwareMappingBuilder();
        $indexDefinitionTransfer = (new IndexDefinitionTransfer())->setMappings($this->fixtureMappingData);
        $mapping = $mappingBuilder->buildMapping($indexDefinitionTransfer, $indexMock);

        foreach (reset($this->fixtureMappingData) as $key => $value) {
            $this->assertEquals($value, $mapping->getParam($key));
        }
    }

    /**
     * @retrn void
     *
     * @return void
     */
    public function testCanBuildTypelessMapping(): void
    {
        if (class_exists('\Elastica\Query\Type')) {
            $this->markTestSkipped('This test can only be run in Elasticsearch 7 (or higher) environment.');
        }

        /** @var \Elastica\Index|\PHPUnit\Framework\MockObject\MockObject $indexMock */
        $indexMock = $this->createMock(Index::class);

        $mappingBuilder = new MappingBuilder();
        $indexDefinitionTransfer = (new IndexDefinitionTransfer())->setMappings($this->fixtureMappingData);
        $mapping = $mappingBuilder->buildMapping($indexDefinitionTransfer, $indexMock);

        foreach (reset($this->fixtureMappingData) as $key => $value) {
            $this->assertEquals($value, $mapping->getParam($key));
        }
    }
}
