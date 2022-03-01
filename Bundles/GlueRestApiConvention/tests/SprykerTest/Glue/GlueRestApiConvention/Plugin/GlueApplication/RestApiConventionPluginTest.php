<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionDependencyProvider;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueApplication\RestApiConventionPlugin;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestBuilderPluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestValidatorPluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseFormatterPluginInterface;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueApplication
 * @group RestApiConventionPluginTest
 * Add your own group annotations below this line
 */
class RestApiConventionPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConventionTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const TEST_VALUE = 'TEST_VALUE';

    /**
     * @return void
     */
    public function testRestApiConventionPluginIsApplicable(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $restApiConventionPlugin = $this->createRestApiConventionPlugin();
        $isApplicable = $restApiConventionPlugin->isApplicable($glueRequestTransfer);

        //Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testRestApiConventionPluginGetName(): void
    {
        //Act
        $restApiConventionPlugin = $this->createRestApiConventionPlugin();
        $restApiConventionName = $restApiConventionPlugin->getName();

        //Assert
        $this->assertSame(GlueRestApiConventionConfig::CONVENTION_REST_API, $restApiConventionName);
    }

    /**
     * @return void
     */
    public function testRestApiConventionPluginGetResourceType(): void
    {
        //Act
        $restApiConventionPlugin = $this->createRestApiConventionPlugin();
        $restApiApiConventionResourceType = $restApiConventionPlugin->getResourceType();

        //Assert
        $this->assertSame(RestResourceInterface::class, $restApiApiConventionResourceType);
    }

    /**
     * @return void
     */
    public function testRestApiConventionPluginBuildRequest(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueRestApiConventionDependencyProvider::PLUGINS_REQUEST_BUILDER,
            $this->createArrayOfRequestBuilderPluginMock(),
        );
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $restApiConventionPlugin = $this->createRestApiConventionPlugin();
        $glueRequestTransfer = $restApiConventionPlugin->buildRequest($glueRequestTransfer);

        //Assert
        $this->assertSame(static::TEST_VALUE, $glueRequestTransfer->getAcceptedFormat());
    }

    /**
     * @return void
     */
    public function testRestApiConventionPluginValidateRequest(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueRestApiConventionDependencyProvider::PLUGINS_REQUEST_VALIDATOR,
            $this->createArrayOfRequestValidatorPluginMock(),
        );
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $restApiConventionPlugin = $this->createRestApiConventionPlugin();
        $glueRequestValidationTransfer = $restApiApiConventionName = $restApiConventionPlugin->validateRequest($glueRequestTransfer);

        //Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testRestApiConventionPluginValidateRequestAfterRouting(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueRestApiConventionDependencyProvider::PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR,
            $this->createArrayOfRequestAfterRoutingValidatorPluginInterfaceMock(),
        );
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();
        $restApiResourceInterfaceMock = $this->getMockBuilder(RestResourceInterface::class)->getMock();

        //Act
        $restApiConventionPlugin = $this->createRestApiConventionPlugin();
        $glueRequestValidationTransfer = $restApiConventionPlugin->validateRequestAfterRouting($glueRequestTransfer, $restApiResourceInterfaceMock);

        //Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testRestApiConventionPluginFormatResponse(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueRestApiConventionDependencyProvider::PLUGINS_RESPONSE_FORMATTER,
            $this->createArrayOfResponseFormatterPluginInterfaceMock(),
        );
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();
        $glueResponseTransfer = $this->tester->createGlueResponseTransfer();

        //Act
        $restApiConventionPlugin = $this->createRestApiConventionPlugin();
        $glueResponseTransfer = $restApiConventionPlugin->formatResponse($glueResponseTransfer, $glueRequestTransfer);

        //Assert
        $this->assertSame(static::TEST_VALUE, $glueResponseTransfer->getContent());
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface
     */
    protected function createRestApiConventionPlugin(): ApiConventionPluginInterface
    {
        return new RestApiConventionPlugin();
    }

    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    protected function createArrayOfRequestBuilderPluginMock(): array
    {
        $firstRequestBuilderPluginInterfaceMock = $this
            ->getMockBuilder(RequestBuilderPluginInterface::class)
            ->getMock();
        $firstRequestBuilderPluginInterfaceMock->expects($this->once())
            ->method('build')
            ->willReturn((new GlueRequestTransfer())->setAcceptedFormat(static::TEST_VALUE));

        $secondRequestBuilderPluginInterfaceMock = $this
            ->getMockBuilder(RequestBuilderPluginInterface::class)
            ->getMock();
        $secondRequestBuilderPluginInterfaceMock->expects($this->once())
            ->method('build')
            ->willReturn((new GlueRequestTransfer())->setAcceptedFormat(static::TEST_VALUE));

        return [
            $firstRequestBuilderPluginInterfaceMock,
            $secondRequestBuilderPluginInterfaceMock,
        ];
    }

    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function createArrayOfRequestValidatorPluginMock(): array
    {
        $firstRequestValidatorPluginInterfaceMock = $this
            ->getMockBuilder(RequestValidatorPluginInterface::class)
            ->getMock();
        $firstRequestValidatorPluginInterfaceMock->expects($this->once())
            ->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        $secondRequestValidatorPluginInterfaceMock = $this
            ->getMockBuilder(RequestValidatorPluginInterface::class)
            ->getMock();
        $secondRequestValidatorPluginInterfaceMock->expects($this->once())
            ->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(false));

        $thirdRequestValidatorPluginInterfaceMock = $this
            ->getMockBuilder(RequestValidatorPluginInterface::class)
            ->getMock();
        $thirdRequestValidatorPluginInterfaceMock->expects($this->never())
            ->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        return [
            $firstRequestValidatorPluginInterfaceMock,
            $secondRequestValidatorPluginInterfaceMock,
            $thirdRequestValidatorPluginInterfaceMock,
        ];
    }

    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    protected function createArrayOfRequestAfterRoutingValidatorPluginInterfaceMock(): array
    {
        $firstRequestAfterRoutingValidatorPluginInterfaceMock = $this
            ->getMockBuilder(RequestAfterRoutingValidatorPluginInterface::class)
            ->getMock();
        $firstRequestAfterRoutingValidatorPluginInterfaceMock->expects($this->once())
            ->method('validateRequest')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        $secondRequestAfterRoutingValidatorPluginInterfaceMock = $this
            ->getMockBuilder(RequestAfterRoutingValidatorPluginInterface::class)
            ->getMock();
        $secondRequestAfterRoutingValidatorPluginInterfaceMock->expects($this->once())
            ->method('validateRequest')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(false));

        $thirdRequestAfterRoutingValidatorPluginInterfaceMock = $this
            ->getMockBuilder(RequestAfterRoutingValidatorPluginInterface::class)
            ->getMock();
        $thirdRequestAfterRoutingValidatorPluginInterfaceMock->expects($this->never())
            ->method('validateRequest')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        return [
            $firstRequestAfterRoutingValidatorPluginInterfaceMock,
            $secondRequestAfterRoutingValidatorPluginInterfaceMock,
            $thirdRequestAfterRoutingValidatorPluginInterfaceMock,
        ];
    }

    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    protected function createArrayOfResponseFormatterPluginInterfaceMock(): array
    {
        $responseFormatterPluginInterfaceMock = $this
            ->getMockBuilder(ResponseFormatterPluginInterface::class)
            ->getMock();
        $responseFormatterPluginInterfaceMock->expects($this->once())
            ->method('format')
            ->willReturn((new GlueResponseTransfer())->setContent(static::TEST_VALUE));

        return [$responseFormatterPluginInterfaceMock];
    }
}
