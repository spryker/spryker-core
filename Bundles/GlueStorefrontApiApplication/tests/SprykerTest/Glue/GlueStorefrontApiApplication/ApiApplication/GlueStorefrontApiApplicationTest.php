<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplication\ApiApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Application\GlueStorefrontApiApplication;
use Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory;

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

        $glueStorefrontApiApplicationMock = $this->createGlueStorefrontApiApplicationMock('getRequestBuilderPlugins', [$requestBuilderPluginMock]);
        $actualRequestBuilderPlugins = $glueStorefrontApiApplicationMock->provideRequestBuilderPlugins();

        $this->assertEquals([$requestBuilderPluginMock], $actualRequestBuilderPlugins);
    }

    /**
     * @return void
     */
    public function testValidateRequestRunsPluginsTillFirstFailingAndReturnsGlueRequestValidationTransfer()
    {
        $glueStorefrontApiApplicationMock = $this->createGlueStorefrontApiApplicationMock('getRequestValidatorPlugins', $this->createRequestValidatorPluginMocks());
        $actualRequestValidatorPlugins = $glueStorefrontApiApplicationMock->provideRequestValidatorPlugins();

        $this->assertEquals($this->createRequestValidatorPluginMocks(), $actualRequestValidatorPlugins);
    }

    /**
     * @return void
     */
    public function testValidateRequestAfterRoutingRunsPluginsTillFirstFailingAndReturnsGlueRequestValidationTransfer(): void
    {
        $glueStorefrontApiApplicationMock = $this->createGlueStorefrontApiApplicationMock('getRequestAfterRoutingValidatorPlugins', $this->createRequestValidatorAfterRoutingPluginMocks());
        $actualRequestAfterRoutingValidatorPlugins = $glueStorefrontApiApplicationMock->provideRequestAfterRoutingValidatorPlugins();

        $this->assertEquals($this->createRequestValidatorAfterRoutingPluginMocks(), $actualRequestAfterRoutingValidatorPlugins);
    }

    /**
     * @return void
     */
    public function testFormatResponseRunsPluginsAndReturnsGlueRequestTransfer(): void
    {
        $responseFormatterPluginMock = $this->createMock(ResponseFormatterPluginInterface::class);

        $glueStorefrontApiApplicationMock = $this->createGlueStorefrontApiApplicationMock('getResponseFormatterPlugins', [$responseFormatterPluginMock]);
        $actualResponseFormatterPlugins = $glueStorefrontApiApplicationMock->provideResponseFormatterPlugins();

        $this->assertEquals([$responseFormatterPluginMock], $actualResponseFormatterPlugins);
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
        $executableValidatorBuilderPluginMock->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        $executableFailedValidatorBuilderPluginMock = $this->createMock(RequestValidatorPluginInterface::class);
        $executableFailedValidatorBuilderPluginMock->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(false));

        $nonExecutableValidatorBuilderPluginMock = $this->createMock(RequestValidatorPluginInterface::class);
        $nonExecutableValidatorBuilderPluginMock->method('validate');

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
        $executableValidatorAfterRoutingBuilderPluginMock->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        $executableFailedValidatorAfterRoutingBuilderPluginMock = $this->createMock(RequestAfterRoutingValidatorPluginInterface::class);
        $executableFailedValidatorAfterRoutingBuilderPluginMock->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(false));

        $nonExecutableValidatorAfterRoutingBuilderPluginMock = $this->createMock(RequestAfterRoutingValidatorPluginInterface::class);
        $nonExecutableValidatorAfterRoutingBuilderPluginMock
            ->expects($this->never())
            ->method('validate');

        return [
            $executableValidatorAfterRoutingBuilderPluginMock,
            $executableFailedValidatorAfterRoutingBuilderPluginMock,
            $nonExecutableValidatorAfterRoutingBuilderPluginMock,
        ];
    }
}
