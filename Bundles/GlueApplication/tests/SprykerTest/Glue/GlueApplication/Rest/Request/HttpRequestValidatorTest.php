<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Request;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidator;
use Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
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
    public function testValidateWhenAcceptHeaderMissingShouldReturnErrorTransfer(): void
    {
        $request = Request::create('/');
        $httpRequestValidator = $this->createHttpRequestValidator();
        $restErrorMessageTransfer = $httpRequestValidator->validate($request);

        $this->assertEquals(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $restErrorMessageTransfer->getStatus());
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
     * @return \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface|\PHPUnit\Framework\MockObject\MockObject|
     */
    protected function createResourceRouteLoaderMock(): ResourceRouteLoaderInterface
    {
        return $this->getMockBuilder(ResourceRouteLoaderInterface::class)->getMock();
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
}
