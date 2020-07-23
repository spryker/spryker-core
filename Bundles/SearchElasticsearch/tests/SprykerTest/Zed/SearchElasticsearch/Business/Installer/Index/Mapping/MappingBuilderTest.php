<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\Installer\Index\Mapping;

use Codeception\Test\Unit;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilder;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface;
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
 *
 * @property \SprykerTest\Zed\SearchElasticsearch\SearchElasticsearchZedTester $tester
 */
class MappingBuilderTest extends Unit
{
    /**
     * @var string[][][]
     */
    protected $fixtureMappingConfiguration = [
        'dummy_mapping_type' => [
            'foo_key' => ['foo_value'],
            'bar_key' => ['bar_value'],
            'baz_key' => ['baz_value'],
        ],
    ];

    /**
     * @return void
     */
    public function testCanBuildTypelessMapping(): void
    {
        /** @var \Elastica\Index|\PHPUnit\Framework\MockObject\MockObject $indexMock */
        $indexMock = $this->tester->createIndexMock();

        $mappingBuilder = $this->createMappingBuilder();
        $mapping = $mappingBuilder->buildMapping($this->fixtureMappingConfiguration, $indexMock);

        $mappingData = array_shift($this->fixtureMappingConfiguration);

        foreach ($mappingData as $key => $value) {
            $this->assertEquals($value, $mapping->getParam($key));
        }
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface
     */
    protected function createMappingBuilder(): MappingBuilderInterface
    {
        if ($this->tester->supportsMappingTypes()) {
            return new MappingTypeAwareMappingBuilder();
        }

        return new MappingBuilder();
    }
}
