<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Response;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilder;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilderInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestRequest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group Response
 * @group ResponseBuilderTest
 *
 * Add your own group annotations below this line
 */
class ResponseBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildResponseShouldResponseAsArray(): void
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

        $responseBuilder = $this->createResponseBuilder($relationshipLoaderMock);

        $restResponse = $restResponseBuilder->createRestResponse(20);

        $resource = $restResponseBuilder->createRestResource('tests', 1);
        $restResponse->addResource($resource);

        $restRequest = (new RestRequest)->createRestRequest();

        $response = $responseBuilder->buildResponse($restResponse, $restRequest);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('1', $response['data']['id']);
        $this->assertEquals('tests', $response['data']['type']);

        $this->assertCount(1, $response['data']['relationships']);
        $this->assertArrayHasKey('related', $response['data']['relationships']);

        $this->assertCount(1, $response['data']['relationships']['related']['data']);
        $this->assertEquals(1, $response['data']['relationships']['related']['data'][0]['id']);
        $this->assertEquals('related', $response['data']['relationships']['related']['data'][0]['type']);

        $this->assertArrayHasKey('included', $response);
        $this->assertCount(1, $response['included']);

        $this->assertArrayHasKey('links', $response);
        $this->assertArrayHasKey('first', $response['links']);
        $this->assertArrayHasKey('last', $response['links']);
        $this->assertArrayHasKey('next', $response['links']);
        $this->assertArrayHasKey('prev', $response['links']);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface $relationshipLoaderMock
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilderInterface
     */
    protected function createResponseBuilder(
        ResourceRelationshipLoaderInterface $relationshipLoaderMock
    ): ResponseBuilderInterface {

        return new ResponseBuilder($relationshipLoaderMock, '');
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRelationshipPluginInterface
     */
    protected function createResourceRelationshipPluginMock(): ResourceRelationshipPluginInterface
    {
        return $this->getMockBuilder(ResourceRelationshipPluginInterface::class)
            ->setMethods(['addResourceRelationships', 'getRelationshipResourceType'])
            ->getMock();
    }
}
