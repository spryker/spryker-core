<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\ApiApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\MissingResourceInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueBackendApiApplication\Application\GlueBackendApiApplication;
use Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationFactory;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouteMatcherPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueBackendApiApplication
 * @group ApiApplication
 * @group GlueBackendApiApplicationTest
 * Add your own group annotations below this line
 */
class GlueBackendApiApplicationTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildRequestRunsPluginsAndReturnsGlueRequestTransfer(): void
    {
        $requestBuilderPluginMock = $this->createMock(RequestBuilderPluginInterface::class);
        $requestBuilderPluginMock->expects($this->once())
            ->method('build');

        $glueBackendApiApplicationMock = $this->createGlueBackendApiApplicationMock('getRequestBuilderPlugins', [$requestBuilderPluginMock]);
        $glueRequestTransfer = $glueBackendApiApplicationMock->buildRequest(new GlueRequestTransfer());

        $this->assertInstanceOf(GlueRequestTransfer::class, $glueRequestTransfer);
    }

    /**
     * @return void
     */
    public function testValidateRequestRunsPluginsTillFirstFailingAndReturnsGlueRequestValidationTransfer()
    {
        $glueBackendApiApplicationMock = $this->createGlueBackendApiApplicationMock('getRequestValidatorPlugins', $this->createRequestValidatorPluginMocks());
        $glueRequestValidationTransfer = $glueBackendApiApplicationMock->validateRequest(new GlueRequestTransfer());

        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $glueRequestValidationTransfer);
    }

    /**
     * @return void
     */
    public function testRouteRunsPluginsAndReturnsResource(): void
    {
        $routeMatcherPlugins = $this->createMock(RouteMatcherPluginInterface::class);
        $routeMatcherPlugins->expects($this->once())
            ->method('route');

        $glueBackendApiApplicationMock = $this->createGlueBackendApiApplicationMock('getRouteMatcherPlugins', [$routeMatcherPlugins]);
        $resource = $glueBackendApiApplicationMock->route(new GlueRequestTransfer());

        $this->assertInstanceOf(ResourceInterface::class, $resource);
        $this->assertNotInstanceOf(MissingResourceInterface::class, $resource);
    }

    /**
     * @return void
     */
    public function testValidateRequestAfterRoutingRunsPluginsTillFirstFailingAndReturnsGlueRequestValidationTransfer(): void
    {
        $glueBackendApiApplicationMock = $this->createGlueBackendApiApplicationMock('getRequestAfterRoutingValidatorPlugins', $this->createRequestValidatorAfterRoutingPluginMocks());
        $glueRequestValidationTransfer = $glueBackendApiApplicationMock->validateRequestAfterRouting(new GlueRequestTransfer(), $this->createMock(ResourceInterface::class));

        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $glueRequestValidationTransfer);
    }

    /**
     * @return void
     */
    public function testFormatResponseRunsPluginsAndReturnsGlueRequestTransfer(): void
    {
        $reponseFormatterPluginMock = $this->createMock(ResponseFormatterPluginInterface::class);
        $reponseFormatterPluginMock->expects($this->once())
            ->method('format');

        $glueBackendApiApplicationMock = $this->createGlueBackendApiApplicationMock('getResponseFormatterPlugins', [$reponseFormatterPluginMock]);
        $glueResponseTransfer = $glueBackendApiApplicationMock->formatResponse(new GlueResponseTransfer(), new GlueRequestTransfer());

        $this->assertInstanceOf(GlueResponseTransfer::class, $glueResponseTransfer);
    }

    /**
     * @param string $methodName
     * @param array<\PHPUnit\Framework\MockObject\MockObject|mixed> $pluginMocks
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerTest\Glue\GlueBackendApiApplication\ApiApplication\Spryker\Glue\GlueBackendApiApplication\Application\GlueBackendApiApplication
     */
    protected function createGlueBackendApiApplicationMock(string $methodName, array $pluginMocks): GlueBackendApiApplication
    {
        $glueBackendApiApplicationFactoryMock = $this->createMock(GlueBackendApiApplicationFactory::class);
        $glueBackendApiApplicationFactoryMock->method($methodName)->willReturn($pluginMocks);

        $glueBackendApiApplicationMock = $this->getMockBuilder(GlueBackendApiApplication::class)
            ->onlyMethods(['getFactory'])
            ->getMock();
        $glueBackendApiApplicationMock->method('getFactory')->willReturn($glueBackendApiApplicationFactoryMock);

        return $glueBackendApiApplicationMock;
    }

    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function createRequestValidatorPluginMocks(): array
    {
        $executableValidatorBuilderPluginMock = $this->createMock(RequestValidatorPluginInterface::class);
        $executableValidatorBuilderPluginMock
            ->expects($this->once())
            ->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        $executableFailedValidatorBuilderPluginMock = $this->createMock(RequestValidatorPluginInterface::class);
        $executableFailedValidatorBuilderPluginMock
            ->expects($this->once())
            ->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(false));

        $nonExecutableValidatorBuilderPluginMock = $this->createMock(RequestValidatorPluginInterface::class);
        $nonExecutableValidatorBuilderPluginMock
            ->expects($this->never())
            ->method('validate');

        return [
            $executableValidatorBuilderPluginMock,
            $executableFailedValidatorBuilderPluginMock,
            $nonExecutableValidatorBuilderPluginMock,
            ];
    }

    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    protected function createRequestValidatorAfterRoutingPluginMocks(): array
    {
        $executableValidatorAfterRoutingBuilderPluginMock = $this->createMock(RequestAfterRoutingValidatorPluginInterface::class);
        $executableValidatorAfterRoutingBuilderPluginMock
            ->expects($this->once())
            ->method('validateRequest')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        $executableFailedValidatorAfterRoutingBuilderPluginMock = $this->createMock(RequestAfterRoutingValidatorPluginInterface::class);
        $executableFailedValidatorAfterRoutingBuilderPluginMock
            ->expects($this->once())
            ->method('validateRequest')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(false));

        $nonExecutableValidatorAfterRoutingBuilderPluginMock = $this->createMock(RequestAfterRoutingValidatorPluginInterface::class);
        $nonExecutableValidatorAfterRoutingBuilderPluginMock
            ->expects($this->never())
            ->method('validateRequest');

        return [
            $executableValidatorAfterRoutingBuilderPluginMock,
            $executableFailedValidatorAfterRoutingBuilderPluginMock,
            $nonExecutableValidatorAfterRoutingBuilderPluginMock,
        ];
    }
}
