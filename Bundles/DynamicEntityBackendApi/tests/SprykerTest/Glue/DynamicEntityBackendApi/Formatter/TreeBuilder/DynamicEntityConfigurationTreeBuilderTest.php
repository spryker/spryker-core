<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DynamicEntityBackendApi\Formatter\TreeBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DynamicEntityBackendApi
 * @group Formatter
 * @group TreeBuilder
 * @group DynamicEntityConfigurationTreeBuilderTest
 * Add your own group annotations below this line
 */
class DynamicEntityConfigurationTreeBuilderTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testBuildDynamicEntityConfigurationTransferByDeepLevelReturnsDynamicEntityConfigurationTransfer(): void
    {
        // Arrange
        $dynamicEntityConfigurationTransfer = $this->tester->createDynamicEntityConfigurationTransfer();
        $dynamicEntityConfigurationTreeBuilder = $this->tester->createDynamicEntityConfigurationTreeBuilder();

        // Act
        $dynamicEntityConfigurationTransfer = $dynamicEntityConfigurationTreeBuilder->buildDynamicEntityConfigurationTransferTree($dynamicEntityConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DynamicEntityConfigurationTransfer::class, $dynamicEntityConfigurationTransfer);
        $this->assertCount(0, $dynamicEntityConfigurationTransfer->getChildRelations());
    }

    /**
     * @return void
     */
    public function testBuildDynamicEntityConfigurationTransferByDeepLevelReturnsDynamicEntityConfigurationTransferWithChildEntityConfiguration(): void
    {
        // Arrange
        $dynamicEntityConfigurationTransfer = $this->tester->createDynamicEntityConfigurationTransferWithChildRelationsTree();
        $dynamicEntityConfigurationTreeBuilder = $this->tester->createDynamicEntityConfigurationTreeBuilder();

        // Act
        $dynamicEntityConfigurationTransfer = $dynamicEntityConfigurationTreeBuilder->buildDynamicEntityConfigurationTransferTree($dynamicEntityConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DynamicEntityConfigurationTransfer::class, $dynamicEntityConfigurationTransfer);
        $this->assertCount(1, $dynamicEntityConfigurationTransfer->getChildRelations());
        $this->assertEquals('test-first-level-child-relation', $dynamicEntityConfigurationTransfer->getChildRelations()[0]->getName());
    }

    /**
     * @return void
     */
    public function testBuildDynamicEntityConfigurationTransferByDeepLevelReturnsDynamicEntityConfigurationTransferWithChildEntityConfigurations(): void
    {
        // Arrange
        $dynamicEntityConfigurationTransfer = $this->tester->createDynamicEntityConfigurationTransferWithChildRelations(5);
        $dynamicEntityConfigurationTreeBuilder = $this->tester->createDynamicEntityConfigurationTreeBuilder();

        // Act
        $dynamicEntityConfigurationTransfer = $dynamicEntityConfigurationTreeBuilder->buildDynamicEntityConfigurationTransferTree($dynamicEntityConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DynamicEntityConfigurationTransfer::class, $dynamicEntityConfigurationTransfer);
        $this->assertCount(5, $dynamicEntityConfigurationTransfer->getChildRelations());
    }

    /**
     * @return void
     */
    public function testBuildDynamicEntityConfigurationTransferByDeepLevelReturnsDynamicEntityConfigurationTransferWithTreeChildEntityConfigurations(): void
    {
        // Arrange
        $dynamicEntityConfigurationTransfer = $this->tester->createDynamicEntityConfigurationTransferWithThreeLevels();
        $dynamicEntityConfigurationTreeBuilder = $this->tester->createDynamicEntityConfigurationTreeBuilder();

        // Act
        $dynamicEntityConfigurationTransfer = $dynamicEntityConfigurationTreeBuilder->buildDynamicEntityConfigurationTransferTree($dynamicEntityConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DynamicEntityConfigurationTransfer::class, $dynamicEntityConfigurationTransfer);
        $this->assertCount(1, $dynamicEntityConfigurationTransfer->getChildRelations());
        $this->assertEquals('test-first-level-child-relation', $dynamicEntityConfigurationTransfer->getChildRelations()[0]->getName());
        $this->assertCount(
            1,
            $dynamicEntityConfigurationTransfer->getChildRelations()[0]->getChildDynamicEntityConfiguration()->getChildRelations(),
        );
        $this->assertEquals(
            'test-second-level-child-relation',
            $dynamicEntityConfigurationTransfer->getChildRelations()[0]->getChildDynamicEntityConfiguration()->getChildRelations()[0]->getName(),
        );
        $this->assertCount(
            1,
            $dynamicEntityConfigurationTransfer->getChildRelations()[0]->getChildDynamicEntityConfiguration()->getChildRelations()[0]->getChildDynamicEntityConfiguration()->getChildRelations(),
        );
        $this->assertEquals(
            'test-third-level-child-relation',
            $dynamicEntityConfigurationTransfer->getChildRelations()[0]->getChildDynamicEntityConfiguration()->getChildRelations()[0]->getChildDynamicEntityConfiguration()->getChildRelations()[0]->getName(),
        );
    }

    /**
     * @return void
     */
    public function testBuildDynamicEntityConfigurationTransferByDeepLevelReturnsDynamicEntityConfigurationTransferWithTreeChildEntityConfigurationsToSecondLevel(): void
    {
        // Arrange
        $dynamicEntityConfigurationTransfer = $this->tester->createDynamicEntityConfigurationTransferWithThreeLevels();
        $dynamicEntityConfigurationTreeBuilder = $this->tester->createDynamicEntityConfigurationTreeBuilder();

        // Act
        $dynamicEntityConfigurationTransfer = $dynamicEntityConfigurationTreeBuilder->buildDynamicEntityConfigurationTransferTree($dynamicEntityConfigurationTransfer, 2);

        // Assert
        $this->assertInstanceOf(DynamicEntityConfigurationTransfer::class, $dynamicEntityConfigurationTransfer);
        $this->assertCount(1, $dynamicEntityConfigurationTransfer->getChildRelations());
        $this->assertEquals('test-first-level-child-relation', $dynamicEntityConfigurationTransfer->getChildRelations()[0]->getName());
        $this->assertCount(1, $dynamicEntityConfigurationTransfer->getChildRelations()[0]->getChildDynamicEntityConfiguration()->getChildRelations());
        $this->assertEquals(
            'test-second-level-child-relation',
            $dynamicEntityConfigurationTransfer->getChildRelations()[0]->getChildDynamicEntityConfiguration()->getChildRelations()[0]->getName(),
        );
    }

    /**
     * @return void
     */
    public function testBuildDynamicEntityConfigurationTransferByDeepLevelReturnsDynamicEntityConfigurationTransferWithTreeChildEntityConfigurationsToFirstLevel(): void
    {
        // Arrange
        $dynamicEntityConfigurationTransfer = $this->tester->createDynamicEntityConfigurationTransferWithThreeLevels();
        $dynamicEntityConfigurationTreeBuilder = $this->tester->createDynamicEntityConfigurationTreeBuilder();

        // Act
        $dynamicEntityConfigurationTransfer = $dynamicEntityConfigurationTreeBuilder->buildDynamicEntityConfigurationTransferTree($dynamicEntityConfigurationTransfer, 1);

        // Assert
        $this->assertInstanceOf(DynamicEntityConfigurationTransfer::class, $dynamicEntityConfigurationTransfer);
        $this->assertCount(1, $dynamicEntityConfigurationTransfer->getChildRelations());
        $this->assertEquals('test-first-level-child-relation', $dynamicEntityConfigurationTransfer->getChildRelations()[0]->getName());
        $this->assertCount(0, $dynamicEntityConfigurationTransfer->getChildRelations()[0]->getChildDynamicEntityConfiguration()->getChildRelations());
    }
}
