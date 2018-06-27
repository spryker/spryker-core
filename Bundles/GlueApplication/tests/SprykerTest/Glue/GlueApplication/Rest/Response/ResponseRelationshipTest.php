<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
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
 * Auto-generated group annotations
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
                function (array $resources, RestRequestInterface $restRequest) use ($restResponseBuilder) {
                    foreach ($resources as $resource) {
                        $resource->addRelationship(
                            $restResponseBuilder->createRestResource('related', 1)
                        );
                    }
                }
            );

        $relationshipLoaderMock
            ->method('load')
            ->willReturn([$relationshipPluginMock]);

        $responseRelationship = $this->createResponseRelationship($relationshipLoaderMock);

        $resource = $restResponseBuilder->createRestResource('tests', 1);

        $restRequest = (new RestRequest)->createRestRequest();

        $responseRelationship->loadRelationships('tests', [$resource], $restRequest);

        $this->assertCount(1, $resource->getRelationships());

        $this->assertCount(1, $resource->getRelationships()['related']);
        $this->assertEquals(1, $resource->getRelationships()['related'][0]->getId());
        $this->assertEquals('related', $resource->getRelationships()['related'][0]->getType());
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

        $restRequest = (new RestRequest)->createRestRequest();

        $included = $responseRelationship->processIncluded([$resource], $restRequest);

        $this->assertCount(1, $included);
        $this->assertEquals('related', $included[0]->getType());
        $this->assertEquals('1', $included[0]->getId());
    }

    /**
     * @return void
     */
    public function testHasRelationshipShouldReturnTrueWhenIncludedOmitted(): void
    {
        $responseRelationship = $this->createResponseRelationship();

        $restRequest = (new RestRequest)->createRestRequest();

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
            ->setMethods(['load'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface
     */
    protected function createResourceRelationshipPluginMock(): ResourceRelationshipPluginInterface
    {
        return $this->getMockBuilder(ResourceRelationshipPluginInterface::class)
            ->setMethods(['addResourceRelationships', 'getRelationshipResourceType'])
            ->getMock();
    }
}
