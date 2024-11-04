<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Response;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseRelationship;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseRelationshipInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestRequest;

/**
 * @deprecated Will be removed without replacement.
 *
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group Response
 * @group ResponseRelationshipTest
 *
 * Add your own group annotations below this line
 */
class ResponseRelationshipTest extends Unit
{
    /**
     * @var string
     */
    protected const RESOURCE_TYPE_PRODUCT_ABSTRACT = 'product-abstract';

    /**
     * @var string
     */
    protected const RESOURCE_TYPE_PRODUCT_CONCRETE = 'product-concrete';

    /**
     * @var string
     */
    protected const RESOURCE_TYPE_IMAGE_SET = 'concrete-product-image-sets';

    /**
     * @var int
     */
    protected const RESOURCE_ABSTRACT_PRODUCT_ID = 1;

    /**
     * @var int
     */
    protected const RESOURCE_IMAGE_SET_ID = 1;

    /**
     * @var int
     */
    protected const RESOURCE_CONCRETE_PRODUCT_ID = 2;

    /**
     * @return void
     */
    public function testLoadRelationshipsShouldIncludeRelationsByPlugin(): void
    {
        $restResponseBuilder = new RestResourceBuilder();

        $relationshipLoaderMock = $this->createRelationshipLoaderMock();

        $relationshipPluginMock = $this->createResourceRelationshipPluginMock();

        $relationshipPluginMock
            ->method('addResourceRelationships')
            ->willReturnCallback(
                function (array $resources, RestRequestInterface $restRequest) use ($restResponseBuilder): void {
                    foreach ($resources as $resource) {
                        $resource->addRelationship(
                            $restResponseBuilder->createRestResource('related', 1),
                        );
                    }
                },
            );

        $relationshipLoaderMock
            ->method('load')
            ->willReturn([$relationshipPluginMock]);

        $responseRelationship = $this->createResponseRelationship($relationshipLoaderMock);

        $resource = $restResponseBuilder->createRestResource('tests', 1);

        $restRequest = (new RestRequest())->createRestRequest();

        $responseRelationship->loadRelationships('tests', [$resource], $restRequest);

        $actualRelationships = $resource->getRelationships()['related'];
        $this->assertCount(1, $actualRelationships);

        $firstRelation = reset($actualRelationships);
        $this->assertSame('1', $firstRelation->getId());
        $this->assertSame('related', $firstRelation->getType());
    }

    /**
     * @return void
     */
    public function testProcessIncludedShouldReturnIncludedFromRelationships(): void
    {
        $responseRelationship = $this->createResponseRelationship();

        $restResponseBuilder = new RestResourceBuilder();
        $resource = $restResponseBuilder->createRestResource('tests', 1);
        $resourceRelated = $restResponseBuilder->createRestResource('related', 1);
        $resource->addRelationship($resourceRelated);

        $restRequest = (new RestRequest())->createRestRequest();

        $included = $responseRelationship->processIncluded([$resource], $restRequest);

        $this->assertCount(1, $included);
        $this->assertSame('related', $included[0]->getType());
        $this->assertSame('1', $included[0]->getId());
    }

    /**
     * @return void
     */
    public function testProcessIncludedDoesntOverrideResourceWithRelationship(): void
    {
        // Arrange
        $restResourceBuilder = new RestResourceBuilder();
        $restRequest = (new RestRequest())->createRestRequest();

        $relationshipLoaderMock = $this->createRelationshipLoaderMock();
        $responseRelationship = $this->createResponseRelationship($relationshipLoaderMock);

        $resources = $this->createResourcesWithOverwritableRelations($restResourceBuilder);

        // Act
        /**
         * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $includeAbstractProduct
         * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $includeImageSet
         * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $includeConcreteProduct
         */
        [$includeAbstractProduct, $includeImageSet, $includeConcreteProduct] = $responseRelationship
            ->processIncluded($resources, $restRequest);

        // Assert
        $this->assertSame(static::RESOURCE_TYPE_PRODUCT_ABSTRACT, $includeAbstractProduct->getType());
        $this->assertEquals(static::RESOURCE_ABSTRACT_PRODUCT_ID, $includeAbstractProduct->getId());

        $this->assertSame(static::RESOURCE_TYPE_IMAGE_SET, $includeImageSet->getType());
        $this->assertEquals(static::RESOURCE_IMAGE_SET_ID, $includeImageSet->getId());

        $this->assertSame(static::RESOURCE_TYPE_PRODUCT_CONCRETE, $includeConcreteProduct->getType());
        $this->assertEquals(static::RESOURCE_CONCRETE_PRODUCT_ID, $includeConcreteProduct->getId());

        $concreteProductIncludesItemRelations = $includeConcreteProduct->getRelationships();

        $this->assertCount(2, $concreteProductIncludesItemRelations);

        $this->assertArrayHasKey(static::RESOURCE_TYPE_PRODUCT_ABSTRACT, $concreteProductIncludesItemRelations);
        $this->assertCount(1, $concreteProductIncludesItemRelations[static::RESOURCE_TYPE_PRODUCT_ABSTRACT]);

        $this->assertArrayHasKey(static::RESOURCE_TYPE_IMAGE_SET, $concreteProductIncludesItemRelations);
        $this->assertCount(1, $concreteProductIncludesItemRelations[static::RESOURCE_TYPE_IMAGE_SET]);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder $restResourceBuilder
     *
     * @return array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    protected function createResourcesWithOverwritableRelations(RestResourceBuilder $restResourceBuilder): array
    {
        $abstractProductResource = $restResourceBuilder->createRestResource(
            static::RESOURCE_TYPE_PRODUCT_ABSTRACT,
            static::RESOURCE_ABSTRACT_PRODUCT_ID,
        );

        $imageSetResource = $restResourceBuilder->createRestResource(
            static::RESOURCE_TYPE_IMAGE_SET,
            static::RESOURCE_IMAGE_SET_ID,
        );

        $concreteProduct = $restResourceBuilder->createRestResource(
            static::RESOURCE_TYPE_PRODUCT_CONCRETE,
            static::RESOURCE_CONCRETE_PRODUCT_ID,
        );

        $concreteProduct->addRelationship(
            $restResourceBuilder->createRestResource(
                static::RESOURCE_TYPE_PRODUCT_ABSTRACT,
                static::RESOURCE_ABSTRACT_PRODUCT_ID,
            ),
        );

        $concreteProduct->addRelationship(
            $restResourceBuilder->createRestResource(
                static::RESOURCE_TYPE_IMAGE_SET,
                static::RESOURCE_IMAGE_SET_ID,
            ),
        );

        $abstractProductResource->addRelationship($concreteProduct);

        $imageSetResource->addRelationship(
            $restResourceBuilder->createRestResource(
                static::RESOURCE_TYPE_PRODUCT_CONCRETE,
                static::RESOURCE_CONCRETE_PRODUCT_ID,
            ),
        );

        return [$abstractProductResource, $imageSetResource];
    }

    /**
     * @return void
     */
    public function testHasRelationshipShouldReturnTrueWhenIncludedOmitted(): void
    {
        $responseRelationship = $this->createResponseRelationship();

        $restRequest = (new RestRequest())->createRestRequest();

        $this->assertTrue($responseRelationship->hasRelationship('test', $restRequest));
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface|null $relationshipLoaderMock
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseRelationshipInterface
     */
    protected function createResponseRelationship(
        ?ResourceRelationshipLoaderInterface $relationshipLoaderMock = null
    ): ResponseRelationshipInterface {
        if (!$relationshipLoaderMock) {
            $relationshipLoaderMock = $this->createRelationshipLoaderMock();
        }

        return new ResponseRelationship($relationshipLoaderMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface
     */
    protected function createRelationshipLoaderMock(): ResourceRelationshipLoaderInterface
    {
        return $this->getMockBuilder(ResourceRelationshipLoaderInterface::class)
            ->onlyMethods(['load'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface
     */
    protected function createResourceRelationshipPluginMock(): ResourceRelationshipPluginInterface
    {
        return $this->getMockBuilder(ResourceRelationshipPluginInterface::class)
            ->onlyMethods(['addResourceRelationships', 'getRelationshipResourceType'])
            ->getMock();
    }
}
