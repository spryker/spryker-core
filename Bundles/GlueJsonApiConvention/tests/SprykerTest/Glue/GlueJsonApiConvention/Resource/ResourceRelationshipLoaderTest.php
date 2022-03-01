<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Resource;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\GlueJsonApiConvention\Resource\ResourceRelationshipLoader;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RelationshipProviderPluginInterface;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Resource
 * @group ResourceRelationshipLoaderTest
 *
 * Add your own group annotations below this line
 */
class ResourceRelationshipLoaderTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_RESOURCE_NAME = 'Foo';

    /**
     * @return void
     */
    public function testLoadWithTwoApplicablePluginsShouldReturnRelationshipsForBoth(): void
    {
        //arrange
        $relationshipProviderPluginMocks = [
            $this->createApplicableRelationshipProviderPluginMock(),
            $this->createApplicableRelationshipProviderPluginMock(),
        ];

        //act
        $resourceRelationshipLoader = new ResourceRelationshipLoader($relationshipProviderPluginMocks);
        $loadedResourceRelationships = $resourceRelationshipLoader->load(static::FAKE_RESOURCE_NAME, new GlueRequestTransfer());

        //assert
        $this->assertCount(2, $loadedResourceRelationships);
    }

    /**
     * @return void
     */
    public function testLoadWithTwoPluginsShouldReturnRelationshipsOnlyForApplicable(): void
    {
        //arrange
        $relationshipProviderPluginMocks = [
            $this->createApplicableRelationshipProviderPluginMock(),
            $this->createNonapplicableRelationshipProviderPluginMock(),
            $this->createApplicableRelationshipProviderPluginMock(),
        ];

        //act
        $resourceRelationshipLoader = new ResourceRelationshipLoader($relationshipProviderPluginMocks);
        $loadedResourceRelationships = $resourceRelationshipLoader->load(static::FAKE_RESOURCE_NAME, new GlueRequestTransfer());

        //assert
        $this->assertCount(2, $loadedResourceRelationships);
    }

    /**
     * @return void
     */
    public function testLoadWithNonapplicablePluginsShouldReturnEmptyArray(): void
    {
        //arrange
        $relationshipProviderPluginMocks = [
            $this->createNonapplicableRelationshipProviderPluginMock(),
        ];

        //act
        $resourceRelationshipLoader = new ResourceRelationshipLoader($relationshipProviderPluginMocks);
        $loadedResourceRelationships = $resourceRelationshipLoader->load(static::FAKE_RESOURCE_NAME, new GlueRequestTransfer());

        //assert
        $this->assertEmpty($loadedResourceRelationships);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RelationshipProviderPluginInterface
     */
    protected function createApplicableRelationshipProviderPluginMock(): RelationshipProviderPluginInterface
    {
        $relationshipProviderPluginMock = $this->createMock(RelationshipProviderPluginInterface::class);
        $relationshipProviderPluginMock->expects($this->once())
            ->method('isApplicable')
            ->willReturn(true);
        $relationshipProviderPluginMock->expects($this->once())
            ->method('getResourceRelationshipCollection')
            ->willReturn(
                $this->createResourceRelationshipCollectionMock(),
            );

        return $relationshipProviderPluginMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RelationshipProviderPluginInterface
     */
    protected function createNonapplicableRelationshipProviderPluginMock(): RelationshipProviderPluginInterface
    {
        $relationshipProviderPluginMock = $this->createMock(RelationshipProviderPluginInterface::class);
        $relationshipProviderPluginMock->expects($this->once())
            ->method('isApplicable')
            ->willReturn(false);
        $relationshipProviderPluginMock->expects($this->never())
            ->method('getResourceRelationshipCollection');

        return $relationshipProviderPluginMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    protected function createResourceRelationshipCollectionMock(): ResourceRelationshipCollectionInterface
    {
        $resourceRelationshipCollectionMock = $this->createMock(ResourceRelationshipCollectionInterface::class);
        $resourceRelationshipCollectionMock->expects($this->once())
            ->method('hasRelationships')
            ->willReturn(true);
        $resourceRelationshipCollectionMock->expects($this->once())
            ->method('getRelationships')
            ->willReturn([$this->createMock(ResourceRelationshipPluginInterface::class)]);

        return $resourceRelationshipCollectionMock;
    }
}
