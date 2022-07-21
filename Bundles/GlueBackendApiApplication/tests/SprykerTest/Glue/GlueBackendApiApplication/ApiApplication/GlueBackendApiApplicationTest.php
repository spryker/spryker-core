<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\ApiApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface;
use Spryker\Glue\GlueBackendApiApplication\Application\GlueBackendApiApplication;
use Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationFactory;

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

        $glueBackendApiApplicationMock = $this->createGlueBackendApiApplicationMock('getRequestBuilderPlugins', [$requestBuilderPluginMock]);
        $actualRequestBuilderPlugins = $glueBackendApiApplicationMock->provideRequestBuilderPlugins();

        $this->assertEquals([$requestBuilderPluginMock], $actualRequestBuilderPlugins);
    }

    /**
     * @return void
     */
    public function testValidateRequestRunsPluginsTillFirstFailingAndReturnsGlueRequestValidationTransfer()
    {
        $glueBackendApiApplicationMock = $this->createGlueBackendApiApplicationMock('getRequestValidatorPlugins', $this->createRequestValidatorPluginMocks());
        $actualRequestValidatorPlugins = $glueBackendApiApplicationMock->provideRequestValidatorPlugins();

        $this->assertEquals($this->createRequestValidatorPluginMocks(), $actualRequestValidatorPlugins);
    }

    /**
     * @return void
     */
    public function testValidateRequestAfterRoutingRunsPluginsTillFirstFailingAndReturnsGlueRequestValidationTransfer(): void
    {
        $glueBackendApiApplicationMock = $this->createGlueBackendApiApplicationMock('getRequestAfterRoutingValidatorPlugins', $this->createRequestValidatorAfterRoutingPluginMocks());
        $actualRequestValidatorAfterRoutingPlugins = $glueBackendApiApplicationMock->provideRequestAfterRoutingValidatorPlugins();

        $this->assertEquals($this->createRequestValidatorAfterRoutingPluginMocks(), $actualRequestValidatorAfterRoutingPlugins);
    }

    /**
     * @return void
     */
    public function testFormatResponseRunsPluginsAndReturnsGlueRequestTransfer(): void
    {
        $responseFormatterPluginMock = $this->createMock(ResponseFormatterPluginInterface::class);

        $glueBackendApiApplicationMock = $this->createGlueBackendApiApplicationMock('getResponseFormatterPlugins', [$responseFormatterPluginMock]);
        $actualResponseFormatterPlugins = $glueBackendApiApplicationMock->provideResponseFormatterPlugins();

        $this->assertEquals([$responseFormatterPluginMock], $actualResponseFormatterPlugins);
    }

    /**
     * @param string $methodName
     * @param array<\PHPUnit\Framework\MockObject\MockObject|mixed> $pluginMocks
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueBackendApiApplication\Application\GlueBackendApiApplication
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
            ->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        $executableFailedValidatorBuilderPluginMock = $this->createMock(RequestValidatorPluginInterface::class);
        $executableFailedValidatorBuilderPluginMock
            ->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(false));

        $nonExecutableValidatorBuilderPluginMock = $this->createMock(RequestValidatorPluginInterface::class);
        $nonExecutableValidatorBuilderPluginMock
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
            ->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        $executableFailedValidatorAfterRoutingBuilderPluginMock = $this->createMock(RequestAfterRoutingValidatorPluginInterface::class);
        $executableFailedValidatorAfterRoutingBuilderPluginMock
            ->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(false));

        $nonExecutableValidatorAfterRoutingBuilderPluginMock = $this->createMock(RequestAfterRoutingValidatorPluginInterface::class);
        $nonExecutableValidatorAfterRoutingBuilderPluginMock
            ->method('validate');

        return [
            $executableValidatorAfterRoutingBuilderPluginMock,
            $executableFailedValidatorAfterRoutingBuilderPluginMock,
            $nonExecutableValidatorAfterRoutingBuilderPluginMock,
        ];
    }
}
