<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\Installer\Index\Mapping;

use Codeception\Test\Unit;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilder;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingTypeAwareMappingBuilder;
use Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchBusinessFactory;

/**
 * Auto-generated group annotations
 *
 * @deprecated Will be removed after the support of mapping types is dropped.
 *
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Installer
 * @group Index
 * @group Mapping
 * @group MappingBuilderFactoryTest
 * Add your own group annotations below this line
 */
class MappingBuilderFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testCanCreateMappingTypeAwareMappingBuild(): void
    {
        // Arrange
        if (!class_exists('\Elastica\Type')) {
            $this->markTestSkipped('This test can only be run in Elasticsearch 6 (or lower) environment.');
        }

        $mappingBuilderFactory = (new SearchElasticsearchBusinessFactory())->createMappingBuilderFactory();

        // Act
        $mappingBuilder = $mappingBuilderFactory->createMappingBuilder();

        // Assert
        $this->assertInstanceOf(MappingTypeAwareMappingBuilder::class, $mappingBuilder);
    }

    /**
     * @return void
     */
    public function testCanCreateappingBuild(): void
    {
        // Arrange
        if (class_exists('\Elastica\Type')) {
            $this->markTestSkipped('This test can only be run in Elasticsearch 7 (or higher) environment.');
        }

        $mappingBuilderFactory = (new SearchElasticsearchBusinessFactory())->createMappingBuilderFactory();

        // Act
        $mappingBuilder = $mappingBuilderFactory->createMappingBuilder();

        // Assert
        $this->assertInstanceOf(MappingBuilder::class, $mappingBuilder);
    }
}
