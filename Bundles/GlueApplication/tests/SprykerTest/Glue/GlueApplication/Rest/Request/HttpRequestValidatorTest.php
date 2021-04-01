<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Request;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\HeadersValidateHttpRequestPlugin;
use Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidator;
use Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface;
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
        $request = Request::create(
            '/',
            Request::METHOD_GET,
            [],
            [],
            [],
            [
                'HTTP_CONTENT-TYPE' => 'application/vnd.api+json; version=1.0',
                'HTTP_ACCEPT' => 'application/vnd.api+json; version=1.0',
                'HTTP_' . RequestConstantsInterface::HEADER_ACCESS_CONTROL_REQUEST_HEADER => 'INVALID',
            ]
        );

        $httpRequestValidator = $this->createHttpRequestValidator([
            new HeadersValidateHttpRequestPlugin(),
        ]);

        $restErrorMessageTransfer = $httpRequestValidator->validate($request);

        $this->assertSame(Response::HTTP_FORBIDDEN, $restErrorMessageTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testValidateAccessControllRequestMethodError(): void
    {
        $request = Request::create(
            '/',
            Request::METHOD_GET,
            [],
            [],
            [],
            [
                'HTTP_CONTENT-TYPE' => 'application/vnd.api+json; version=1.0',
                'HTTP_ACCEPT' => 'application/vnd.api+json; version=1.0',
                'HTTP_' . RequestConstantsInterface::HEADER_ACCESS_CONTROL_REQUEST_METHOD => 'INVALID',
            ]
        );

        $request->attributes->set(RequestConstantsInterface::ATTRIBUTE_TYPE, 'invalid');
        $request->attributes->set(RequestConstantsInterface::ATTRIBUTE_ALL_RESOURCES, []);

        $httpRequestValidator = $this->createHttpRequestValidator([
            new HeadersValidateHttpRequestPlugin(),
        ]);

        $restErrorMessageTransfer = $httpRequestValidator->validate($request);

        $this->assertSame(Response::HTTP_FORBIDDEN, $restErrorMessageTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testValidateWhenAcceptHeaderMissingShouldReturnUnsupportedMediaTypeError(): void
    {
        $request = Request::create('/');

        $httpRequestValidator = $this->createHttpRequestValidator([
            new HeadersValidateHttpRequestPlugin(new GlueApplicationConfig()),
        ]);

        $restErrorMessageTransfer = $httpRequestValidator->validate($request);

        $this->assertSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $restErrorMessageTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testValidateWhenAcceptHeaderMissingShouldReturnNotAcceptableError(): void
    {
        $request = $this->createRequestWithMockedHeaders();

        $httpRequestValidator = $this->createHttpRequestValidator([
            new HeadersValidateHttpRequestPlugin(new GlueApplicationConfig()),
        ]);

        $restErrorMessageTransfer = $httpRequestValidator->validate($request);

        $this->assertSame(Response::HTTP_NOT_ACCEPTABLE, $restErrorMessageTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testValidateWithoutHeadersValidateHttpRequestPlugin(): void
    {
        $request = $this->createRequestWithMockedHeaders();

        $httpRequestValidator = $this->createHttpRequestValidator();

        $this->assertNull($httpRequestValidator->validate($request));
    }

    /**
     * @return void
     */
    public function testValidateMustExecuteValidationPluginsWhenProvider(): void
    {
        $request = Request::create(
            '/',
            Request::METHOD_GET,
            [],
            [],
            [],
            [
                'HTTP_CONTENT-TYPE' => 'application/vnd.api+json; version=1.0',
                'HTTP_ACCEPT' => 'application/vnd.api+json; version=1.0',
            ]
        );
        $httpValidatorPluginMock = $this->getMockBuilder(ValidateHttpRequestPluginInterface::class)
            ->setMethods(['validate'])
            ->getMock();

        $httpValidatorPluginMock
            ->expects($this->once())
            ->method('validate');

        $httpRequestValidator = $this->createHttpRequestValidator([$httpValidatorPluginMock]);
        $httpRequestValidator->validate($request);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface[] $validatorPlugins
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidatorInterface
     */
    public function createHttpRequestValidator(array $validatorPlugins = []): HttpRequestValidatorInterface
    {
        return new HttpRequestValidator($validatorPlugins, $this->createResourceRouteLoaderMock(), new GlueApplicationConfig());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request;
     */
    protected function createRequestWithMockedHeaders(array $headers = []): Request
    {
        $request = Request::create(
            '/',
            Request::METHOD_GET,
            [],
            [],
            [],
            []
        );

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
