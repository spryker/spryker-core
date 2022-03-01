<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface;
use Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionConfig;
use Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionDependencyProvider;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\JsonApiApiConventionPlugin;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RequestBuilderPluginInterface;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RequestValidatorPluginInterface;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResponseFormatterPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Plugin
 * @group GlueApplication
 * @group JsonApiApiConventionPluginTest
 * Add your own group annotations below this line
 */
class JsonApiApiConventionPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueJsonApiConvention\GlueJsonApiConventionTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const TEST_VALUE = 'TEST_VALUE';

    /**
     * @return void
     */
    public function testJsonApiApiConventionPluginIsApplicable(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();
        $isApplicable = $jsonApiApiConventionPlugin->isApplicable($glueRequestTransfer);

        //Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testJsonApiApiConventionPluginGetName(): void
    {
        //Act
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();
        $jsonApiApiConventionName = $jsonApiApiConventionPlugin->getName();

        //Assert
        $this->assertSame(GlueJsonApiConventionConfig::CONVENTION_JSON_API, $jsonApiApiConventionName);
    }

    /**
     * @return void
     */
    public function testJsonApiApiConventionPluginGetResourceType(): void
    {
        //Act
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();
        $jsonApiApiConventionResourceType = $jsonApiApiConventionPlugin->getResourceType();

        //Assert
        $this->assertSame(JsonApiResourceInterface::class, $jsonApiApiConventionResourceType);
    }

    /**
     * @return void
     */
    public function testJsonApiApiConventionPluginBuildRequest(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueJsonApiConventionDependencyProvider::PLUGINS_REQUEST_BUILDER,
            $this->createArrayOfRequestBuilderPluginMock(),
        );
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();
        $glueRequestTransfer = $jsonApiApiConventionPlugin->buildRequest($glueRequestTransfer);

        //Assert
        $this->assertEmpty($glueRequestTransfer->getIncludedRelationships());
    }

    /**
     * @return void
     */
    public function testJsonApiApiConventionPluginValidateRequest(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueJsonApiConventionDependencyProvider::PLUGINS_REQUEST_VALIDATOR,
            $this->createArrayOfRequestValidatorPluginMock(),
        );
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();
        $glueRequestValidationTransfer = $jsonApiApiConventionPlugin->validateRequest($glueRequestTransfer);

        //Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testJsonApiApiConventionPluginValidateRequestAfterRouting(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueJsonApiConventionDependencyProvider::PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR,
            $this->createArrayOfRequestAfterRoutingValidatorPluginInterfaceMock(),
        );
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();
        $jsonApiResourceInterfaceMock = $this->getMockBuilder(JsonApiResourceInterface::class)->getMock();

        //Act
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();
        $glueRequestValidationTransfer = $jsonApiApiConventionPlugin->validateRequestAfterRouting($glueRequestTransfer, $jsonApiResourceInterfaceMock);

        //Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testJsonApiApiConventionPluginFormatResponse(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueJsonApiConventionDependencyProvider::PLUGINS_RESPONSE_FORMATTER,
            $this->createArrayOfResponseFormatterPluginInterfaceMock(),
        );
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();
        $glueResponseTransfer = $this->tester->createGlueResponseTransfer();

        //Act
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();
        $glueResponseTransfer = $jsonApiApiConventionPlugin->formatResponse($glueResponseTransfer, $glueRequestTransfer);

        //Assert
        $this->assertSame(static::TEST_VALUE, $glueResponseTransfer->getContent());
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface
     */
    protected function createJsonApiApiConventionPlugin(): ApiConventionPluginInterface
    {
        return new JsonApiApiConventionPlugin();
    }

    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    protected function createArrayOfRequestBuilderPluginMock(): array
    {
        $firstRequestBuilderPluginInterfaceMock = $this
            ->getMockBuilder(RequestBuilderPluginInterface::class)
            ->getMock();
        $firstRequestBuilderPluginInterfaceMock->expects($this->once())
            ->method('build')
            ->willReturn(new GlueRequestTransfer());

        $secondRequestBuilderPluginInterfaceMock = $this
            ->getMockBuilder(RequestBuilderPluginInterface::class)
            ->getMock();
        $secondRequestBuilderPluginInterfaceMock->expects($this->once())
            ->method('build')
            ->willReturn(new GlueRequestTransfer());

        return [
            $firstRequestBuilderPluginInterfaceMock,
            $secondRequestBuilderPluginInterfaceMock,
        ];
    }

    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RequestValidatorPluginInterface>
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
     * @return array<\PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
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
     * @return array<\PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
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
