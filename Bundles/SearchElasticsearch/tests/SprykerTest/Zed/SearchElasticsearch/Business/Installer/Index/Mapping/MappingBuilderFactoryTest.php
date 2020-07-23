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
 *
 * @property \SprykerTest\Zed\SearchElasticsearch\SearchElasticsearchZedTester $tester
 */
class MappingBuilderFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testCanCreateMappingBuilder(): void
    {
        // Arrange
        $mappingBuilderFactory = (new SearchElasticsearchBusinessFactory())->createMappingBuilderFactory();

        // Act
        $mappingBuilder = $mappingBuilderFactory->createMappingBuilder();

        // Assert
        if ($this->tester->supportsMappingTypes()) {
            $this->assertInstanceOf(MappingTypeAwareMappingBuilder::class, $mappingBuilder);

            return;
        }

        $this->assertInstanceOf(MappingBuilder::class, $mappingBuilder);
    }
}
