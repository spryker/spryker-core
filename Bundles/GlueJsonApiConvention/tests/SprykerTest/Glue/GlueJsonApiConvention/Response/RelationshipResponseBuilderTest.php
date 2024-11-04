<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Response;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueJsonApiConvention\Resource\ResourceRelationshipLoaderInterface;
use Spryker\Glue\GlueJsonApiConvention\Response\RelationshipResponseBuilder;
use Spryker\Glue\GlueJsonApiConvention\Response\RelationshipResponseBuilderInterface;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Response
 * @group RelationshipResponseBuilderTest
 *
 * Add your own group annotations below this line
 */
class RelationshipResponseBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_PARENT_RESOURCE_NAME = 'parentResource';

    /**
     * @var string
     */
    protected const FAKE_RELATIONSHIP_RESOURCE_NAME = 'relationshipResource';

    /**
     * @return void
     */
    public function testLoadRelationshipsShouldIncludeRelationsByPluginAndIncludedRelationships(): void
    {
        //arrange
        $relationshipPluginMock = $this->createResourceRelationshipPluginMock();
        $relationshipPluginMock
            ->method('addRelationships')
            ->willReturnCallback(
                function (array $resources, GlueRequestTransfer $glueRequestTransfer): void {
                    foreach ($resources as $resource) {
                        $resource->addRelationship(
                            (new GlueRelationshipTransfer())->addResource(
                                $this->createResource(static::FAKE_RELATIONSHIP_RESOURCE_NAME, 1),
                            ),
                        );
                    }
                },
            );
        $relationshipPluginMock
            ->method('getRelationshipResourceType')
            ->willReturn(static::FAKE_RELATIONSHIP_RESOURCE_NAME);

        $relationshipLoaderMock = $this->createRelationshipLoaderMock();
        $relationshipLoaderMock
            ->method('load')
            ->willReturn([$relationshipPluginMock]);

        $glueResponseTransfer = (new GlueResponseTransfer())
            ->setResources(new ArrayObject(
                [
                    $this->createResource(static::FAKE_PARENT_RESOURCE_NAME, 1),
                ],
            ));
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setResource(
                $this->createResource(static::FAKE_PARENT_RESOURCE_NAME, 1),
            )
            ->setIncludedRelationships([static::FAKE_RELATIONSHIP_RESOURCE_NAME]);

        //act
        $glueResponseTransfer = $this->createRelationshipResponseBuilder($relationshipLoaderMock)
            ->buildResponse($glueResponseTransfer, $glueRequestTransfer);

        //assert
        $relationships = $glueResponseTransfer->getResources()[0]->getRelationships();
        $this->assertCount(1, $relationships);
        $this->assertSame(1, $relationships[0]->getResources()[0]->getId());
        $this->assertSame(static::FAKE_RELATIONSHIP_RESOURCE_NAME, $relationships[0]->getResources()[0]->getType());

        $included = $glueResponseTransfer->getIncludedRelationships()->getArrayCopy();
        $this->assertCount(1, $included);
        $this->assertSame(1, $included[0]->getId());
        $this->assertSame(static::FAKE_RELATIONSHIP_RESOURCE_NAME, $included[0]->getType());
    }

    /**
     * @param \Spryker\Glue\GlueJsonApiConvention\Resource\ResourceRelationshipLoaderInterface|null $relationshipLoaderMock
     *
     * @return \Spryker\Glue\GlueJsonApiConvention\Response\RelationshipResponseBuilderInterface
     */
    protected function createRelationshipResponseBuilder(
        ?ResourceRelationshipLoaderInterface $relationshipLoaderMock = null
    ): RelationshipResponseBuilderInterface {
        if (!$relationshipLoaderMock) {
            $relationshipLoaderMock = $this->createRelationshipLoaderMock();
        }

        return new RelationshipResponseBuilder($relationshipLoaderMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueJsonApiConvention\Resource\ResourceRelationshipLoaderInterface
     */
    protected function createRelationshipLoaderMock(): ResourceRelationshipLoaderInterface
    {
        return $this->getMockBuilder(ResourceRelationshipLoaderInterface::class)
            ->addMethods(['isApplicable'])
            ->onlyMethods(['load'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface
     */
    protected function createResourceRelationshipPluginMock(): ResourceRelationshipPluginInterface
    {
        return $this->getMockBuilder(ResourceRelationshipPluginInterface::class)
            ->onlyMethods(['addRelationships', 'getRelationshipResourceType'])
            ->getMock();
    }

    /**
     * @param string $type
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createResource(string $type, int $id): GlueResourceTransfer
    {
        return (new GlueResourceTransfer())
            ->setType($type)
            ->setId($id);
    }
}
