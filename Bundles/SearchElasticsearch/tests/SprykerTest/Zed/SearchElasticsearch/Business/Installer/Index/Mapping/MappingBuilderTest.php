<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\Installer\Index\Mapping;

use Codeception\Test\Unit;
use Elastica\Index;
use Elastica\Type;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilder;

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
     * @return void
     */
    public function testCanBuildMapping(): void
    {
        $fixtureMappingData = [
            'foo_key' => 'foo_value',
            'bar_key' => 'bar_value',
            'baz_key' => 'baz_value',
        ];
        $mappingType = '_doc';

        /** @var \Elastica\Index|\PHPUnit\Framework\MockObject\MockObject $indexMock */
        $indexMock = $this->createMock(Index::class);
        $indexMock->method('getType')->willReturn(new Type($indexMock, $mappingType));

        $mappingBuilder = new MappingBuilder();
        $mapping = $mappingBuilder->buildMapping($indexMock, $mappingType, $fixtureMappingData);

        foreach ($fixtureMappingData as $key => $value) {
            $this->assertEquals($value, $mapping->getParam($key));
        }
    }
}
