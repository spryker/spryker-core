<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Response;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseFormatter;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseFormatterInterface;
use Spryker\Glue\GlueApplication\Rest\Serialize\EncoderMatcherInterface;
use Spryker\Glue\GlueApplication\Serialize\Encoder\EncoderInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestRequest;
use SprykerTest\Glue\GlueApplication\Stub\RestResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group Response
 * @group ResponseFormatterTest
 *
 * Add your own group annotations below this line
 */
class ResponseFormatterTest extends Unit
{
    /**
     * @return void
     */
    public function testFormatNoEncoderFoundShouldReturnError(): void
    {
        $encoderMatcherMock = $this->createEncoderMatcherMock();
        $responseBuilder = $this->createResponseBuilderMock();

        $responseFormatter = $this->createResponseFormatter($encoderMatcherMock, $responseBuilder);

        $restResponse = (new RestResponse())->createRestResponse();
        $restRequest = (new RestRequest())->createRestRequest();

        $response = $responseFormatter->format($restResponse, $restRequest);

        $this->assertEquals(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testFormatWhenErrorProvidedShouldFormatErrorResponse(): void
    {
        $encoderMatcherMock = $this->prepareEncoderMocks();
        $responseBuilder = $this->createResponseBuilderMock();

        $responseFormatter = $this->createResponseFormatter($encoderMatcherMock, $responseBuilder);

        $restResponse = (new RestResponse())->createRestResponse();

        $restErrorTransfer = new RestErrorMessageTransfer();
        $restErrorTransfer
            ->setCode(1)
            ->setDetail('error');

        $restResponse->addError($restErrorTransfer);

        $restRequest = (new RestRequest())->createRestRequest();

        $response = $responseFormatter->format($restResponse, $restRequest);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $responseObject = json_decode($response->getContent());
        $this->assertNotEmpty($responseObject->errors);
        $this->assertEquals(($responseObject->errors[0])->status, 400);
        $this->assertEquals(($responseObject->errors[0])->code, 1);
        $this->assertEquals(($responseObject->errors[0])->detail, "error");
    }

    /**
     * @return void
     */
    public function testFormatSuccessResponse(): void
    {
        $encoderMatcherMock = $this->prepareEncoderMocks();
        $responseBuilder = $this->createResponseBuilderMock();

        $responseBuilder->method('buildResponse')->willReturn(['data' => ['type' => 'test']]);

        $responseFormatter = $this->createResponseFormatter($encoderMatcherMock, $responseBuilder);

        $restResponse = (new RestResponse())->createRestResponse();

        $restRequest = (new RestRequest())->createRestRequest();

        $response = $responseFormatter->format($restResponse, $restRequest);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('{"data":{"type":"test"}}', $response->getContent());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Serialize\Encoder\EncoderInterface
     */
    protected function createEncoderMock(): EncoderInterface
    {
        return $this->getMockBuilder(EncoderInterface::class)
                ->setMethods(['encode'])
            ->getMock();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Serialize\EncoderMatcherInterface $encoderMatcher
     * @param \Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilderInterface $responseBuilder
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseFormatterInterface
     */
    protected function createResponseFormatter(
        EncoderMatcherInterface $encoderMatcher,
        ResponseBuilderInterface $responseBuilder
    ): ResponseFormatterInterface {
        return new ResponseFormatter($encoderMatcher, $responseBuilder);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Serialize\EncoderMatcherInterface
     */
    protected function createEncoderMatcherMock(): EncoderMatcherInterface
    {
        return $this->getMockBuilder(EncoderMatcherInterface::class)->setMethods(['match'])->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilderInterface
     */
    protected function createResponseBuilderMock(): ResponseBuilderInterface
    {
        return $this->getMockBuilder(ResponseBuilderInterface::class)
            ->setMethods(['buildResponse'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Serialize\EncoderMatcherInterface
     */
    protected function prepareEncoderMocks(): EncoderMatcherInterface
    {
        $encoderMock = $this->createEncoderMock();
        $encoderMock->method('encode')->willReturnCallback(function (array $data) {
            return json_encode($data);
        });

        $encoderMatcherMock = $this->createEncoderMatcherMock();
        $encoderMatcherMock->method('match')
            ->willReturn($encoderMock);

        return $encoderMatcherMock;
    }
}
