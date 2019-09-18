<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Response;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilder;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponsePaginationInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseRelationshipInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestRequest;

/**
 * Auto-generated group annotations
 *
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

        $responsePaginationMock = $this->createResponsePaginationMock();

        $responsePaginationMock->expects($this->once())
            ->method('buildPaginationLinks');

        $responseRelationMock = $this->createResponseRelationshipMock();

        $responseRelationMock->expects($this->once())->method('processIncluded');

        $responseBuilder = $this->createResponseBuilder($responsePaginationMock, $responseRelationMock);

        $restResponse = $restResponseBuilder->createRestResponse(20);

        $resource = $restResponseBuilder->createRestResource('tests', 1);
        $restResponse->addResource($resource);

        $restRequest = (new RestRequest())->createRestRequest();

        $response = $responseBuilder->buildResponse($restResponse, $restRequest);

        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('1', $response['data']['id']);
        $this->assertEquals('tests', $response['data']['type']);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Response\ResponsePaginationInterface $responsePaginationMock
     * @param \Spryker\Glue\GlueApplication\Rest\Response\ResponseRelationshipInterface $responseRelationshipMock
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilderInterface
     */
    protected function createResponseBuilder(
        ResponsePaginationInterface $responsePaginationMock,
        ResponseRelationshipInterface $responseRelationshipMock
    ): ResponseBuilderInterface {
        return new ResponseBuilder('', $responsePaginationMock, $responseRelationshipMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Response\ResponsePaginationInterface
     */
    protected function createResponsePaginationMock(): ResponsePaginationInterface
    {
        return $this->getMockBuilder(ResponsePaginationInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Response\ResponseRelationshipInterface
     */
    protected function createResponseRelationshipMock(): ResponseRelationshipInterface
    {
        return $this->getMockBuilder(ResponseRelationshipInterface::class)
            ->getMock();
    }
}
