<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplication\ApiApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\MissingResourceInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Application\GlueStorefrontApiApplication;
use Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory;
use Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface;
use Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface;
use Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface;
use Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface;
use Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouteMatcherPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group ApiApplication
 * @group GlueStorefrontApiApplicationTest
 * Add your own group annotations below this line
 */
class GlueStorefrontApiApplicationTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildRequestRunsPluginsAndReturnsGlueRequestTransfer(): void
    {
        $requestBuilderPluginMock = $this->createMock(RequestBuilderPluginInterface::class);
        $requestBuilderPluginMock->expects($this->once())
            ->method('build');

        $glueStorefrontApiApplicationMock = $this->createGlueStorefrontApiApplicationMock('getRequestBuilderPlugins', [$requestBuilderPluginMock]);
        $glueRequestTransfer = $glueStorefrontApiApplicationMock->buildRequest(new GlueRequestTransfer());

        $this->assertInstanceOf(GlueRequestTransfer::class, $glueRequestTransfer);
    }

    /**
     * @return void
     */
    public function testValidateRequestRunsPluginsTillFirstFailingAndReturnsGlueRequestValidationTransfer()
    {
        $glueStorefrontApiApplicationMock = $this->createGlueStorefrontApiApplicationMock('getRequestValidatorPlugins', $this->createRequestValidatorPluginMocks());
        $glueRequestValidationTransfer = $glueStorefrontApiApplicationMock->validateRequest(new GlueRequestTransfer());

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

        $glueStorefrontApiApplicationMock = $this->createGlueStorefrontApiApplicationMock('getRouteMatcherPlugins', [$routeMatcherPlugins]);
        $resource = $glueStorefrontApiApplicationMock->route(new GlueRequestTransfer());

        $this->assertInstanceOf(ResourceInterface::class, $resource);
        $this->assertNotInstanceOf(MissingResourceInterface::class, $resource);
    }

    /**
     * @return void
     */
    public function testValidateRequestAfterRoutingRunsPluginsTillFirstFailingAndReturnsGlueRequestValidationTransfer(): void
    {
        $glueStorefrontApiApplicationMock = $this->createGlueStorefrontApiApplicationMock('getRequestAfterRoutingValidatorPlugins', $this->createRequestValidatorAfterRoutingPluginMocks());
        $glueRequestValidationTransfer = $glueStorefrontApiApplicationMock->validateRequestAfterRouting(new GlueRequestTransfer(), $this->createMock(ResourceInterface::class));

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

        $glueStorefrontApiApplicationMock = $this->createGlueStorefrontApiApplicationMock('getResponseFormatterPlugins', [$reponseFormatterPluginMock]);
        $glueResponseTransfer = $glueStorefrontApiApplicationMock->formatResponse(new GlueResponseTransfer(), new GlueRequestTransfer());

        $this->assertInstanceOf(GlueResponseTransfer::class, $glueResponseTransfer);
    }

    /**
     * @param string $methodName
     * @param array<\PHPUnit\Framework\MockObject\MockObject|mixed> $pluginMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerTest\Glue\GlueStorefrontApiApplication\ApiApplication\Spryker\Glue\GlueStorefrontApiApplication\Application\GlueStorefrontApiApplication
     */
    protected function createGlueStorefrontApiApplicationMock(string $methodName, array $pluginMock): GlueStorefrontApiApplication
    {
        $glueStorefrontApiApplicationFactoryMock = $this->createMock(GlueStorefrontApiApplicationFactory::class);
        $glueStorefrontApiApplicationFactoryMock->method($methodName)->willReturn($pluginMock);

        $glueStorefrontApiApplicationMock = $this->getMockBuilder(GlueStorefrontApiApplication::class)
            ->onlyMethods(['getFactory'])
            ->getMock();
        $glueStorefrontApiApplicationMock->method('getFactory')->willReturn($glueStorefrontApiApplicationFactoryMock);

        return $glueStorefrontApiApplicationMock;
    }

    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
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
     * @return array<\PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
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
