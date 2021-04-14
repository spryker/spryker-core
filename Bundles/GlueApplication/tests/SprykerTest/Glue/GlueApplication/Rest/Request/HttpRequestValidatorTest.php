<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Request;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\Request\HeadersHttpRequestValidator;
use Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidator;
use Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group Request
 * @group HttpRequestValidatorTest
 *
 * Add your own group annotations below this line
 */
class HttpRequestValidatorTest extends Unit
{
    /**
     * @return void
     */
    public function testValidateAccessControllRequestHeaderError(): void
    {
        $request = $this->createRequestWithHeaders([
            'HTTP_CONTENT-TYPE' => 'application/vnd.api+json; version=1.0',
            'HTTP_ACCEPT' => 'application/vnd.api+json; version=1.0',
            'HTTP_' . RequestConstantsInterface::HEADER_ACCESS_CONTROL_REQUEST_HEADER => 'INVALID',
        ]);

        $httpRequestValidator = $this->createHttpRequestValidator();

        $restErrorMessageTransfer = $httpRequestValidator->validate($request);

        $this->assertSame(Response::HTTP_FORBIDDEN, $restErrorMessageTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testValidateAccessControllRequestMethodError(): void
    {
        $request = $this->createRequestWithHeaders([
            'HTTP_CONTENT-TYPE' => 'application/vnd.api+json; version=1.0',
            'HTTP_ACCEPT' => 'application/vnd.api+json; version=1.0',
            'HTTP_' . RequestConstantsInterface::HEADER_ACCESS_CONTROL_REQUEST_METHOD => 'INVALID',
        ]);

        $request->attributes->set(RequestConstantsInterface::ATTRIBUTE_TYPE, 'invalid');
        $request->attributes->set(RequestConstantsInterface::ATTRIBUTE_ALL_RESOURCES, []);

        $httpRequestValidator = $this->createHttpRequestValidator();

        $restErrorMessageTransfer = $httpRequestValidator->validate($request);

        $this->assertSame(Response::HTTP_FORBIDDEN, $restErrorMessageTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testValidateWhenAcceptHeaderMissingShouldReturnUnsupportedMediaTypeError(): void
    {
        $request = Request::create('/');

        $httpRequestValidator = $this->createHttpRequestValidator();

        $restErrorMessageTransfer = $httpRequestValidator->validate($request);

        $this->assertSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $restErrorMessageTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testValidateWhenAcceptHeaderMissingShouldReturnNotAcceptableError(): void
    {
        $request = $this->createRequestWithMockedHeaders();

        $httpRequestValidator = $this->createHttpRequestValidator();

        $restErrorMessageTransfer = $httpRequestValidator->validate($request);

        $this->assertSame(Response::HTTP_NOT_ACCEPTABLE, $restErrorMessageTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testValidateWithoutHeadersValidateHttpRequestPlugin(): void
    {
        $request = $this->createRequestWithMockedHeaders();

        $httpRequestValidator = $this->createHttpRequestValidator(false);

        $this->assertNull($httpRequestValidator->validate($request));
    }

    /**
     * @return void
     */
    public function testValidateMustExecuteValidationPluginsWhenProvider(): void
    {
        $request = $this->createRequestWithHeaders([
            'HTTP_CONTENT-TYPE' => 'application/vnd.api+json; version=1.0',
            'HTTP_ACCEPT' => 'application/vnd.api+json; version=1.0',
        ]);

        $httpRequestValidator = $this->createHttpRequestValidator();
        $httpRequestValidator->validate($request);
    }

    /**
     * @param bool $headerValidationEnabled
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidatorInterface
     */
    public function createHttpRequestValidator(bool $headerValidationEnabled = true): HttpRequestValidatorInterface
    {
        $resourceRouteLoaderMock = $this->createResourceRouteLoaderMock();
        $glueApplicationConfigMock = $this->getMockBuilder(GlueApplicationConfig::class)
            ->getMock();

        $glueApplicationConfigMock->expects($this->any())
            ->method('getValidateRequestHeaders')
            ->willReturn($headerValidationEnabled);

        return new HttpRequestValidator(
            [],
            $resourceRouteLoaderMock,
            $glueApplicationConfigMock,
            new HeadersHttpRequestValidator(
                new GlueApplicationConfig(),
                $resourceRouteLoaderMock
            )
        );
    }

    /**
     * @param array $headers
     *
     * @return \Symfony\Component\HttpFoundation\Request;
     */
    protected function createRequestWithHeaders(array $headers)
    {
        return Request::create('/', Request::METHOD_GET, [], [], [], $headers);
    }

    /**
     * @param array $headers
     *
     * @return \Symfony\Component\HttpFoundation\Request;
     */
    protected function createRequestWithMockedHeaders(array $headers = []): Request
    {
        $request = Request::create('/', Request::METHOD_GET, [], [], [], []);

        $request->headers = $this->getMockBuilder(HeaderBag::class)
            ->disableOriginalConstructor()
            ->setMethods(['all'])
            ->getMock();

        $request->headers->expects($this->any())
            ->method('all')
            ->willReturn($headers);

        return $request;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createResourceRouteLoaderMock(): ResourceRouteLoaderInterface
    {
        return $this->getMockBuilder(ResourceRouteLoaderInterface::class)->getMock();
    }
}
