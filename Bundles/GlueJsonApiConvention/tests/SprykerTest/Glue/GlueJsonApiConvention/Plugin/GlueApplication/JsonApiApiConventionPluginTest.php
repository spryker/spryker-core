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
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface;
use Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionConfig;
use Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionDependencyProvider;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\JsonApiConventionPlugin;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * @uses \Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\JsonApiConventionPlugin::HEADER_CONTENT_TYPE
     *
     * @var string
     */
    protected const HEADER_CONTENT_TYPE = 'content-type';

    /**
     * @var string
     */
    protected const CONTENT_TYPE = 'application/json';

    /**
     * @return void
     */
    public function testJsonApiApiConventionPluginIsApplicableByContentTypeHeader(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        // Act
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();
        $isApplicable = $jsonApiApiConventionPlugin->isApplicable($glueRequestTransfer);

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testJsonApiApiConventionPluginIsNotApplicableByContentTypeHeader(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();
        $glueRequestTransfer->setMeta([static::HEADER_CONTENT_TYPE => [static::CONTENT_TYPE]]);

        // Act
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();
        $isApplicable = $jsonApiApiConventionPlugin->isApplicable($glueRequestTransfer);

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testJsonApiApiConventionPluginIsApplicableByAcceptHeader(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransferWithAcceptHeader();

        // Act
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();
        $isApplicable = $jsonApiApiConventionPlugin->isApplicable($glueRequestTransfer);

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testJsonApiApiConventionPluginIsNotApplicableByAcceptHeader(): void
    {
        // Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransferWithAcceptHeader();
        $glueRequestTransfer->setMethod(Request::METHOD_POST);
        // Act
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();
        $isApplicable = $jsonApiApiConventionPlugin->isApplicable($glueRequestTransfer);

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testJsonApiApiConventionPluginGetName(): void
    {
        // Act
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();
        $jsonApiApiConventionName = $jsonApiApiConventionPlugin->getName();

        // Assert
        $this->assertSame(GlueJsonApiConventionConfig::CONVENTION_JSON_API, $jsonApiApiConventionName);
    }

    /**
     * @return void
     */
    public function testJsonApiApiConventionPluginGetResourceType(): void
    {
        // Act
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();
        $jsonApiApiConventionResourceType = $jsonApiApiConventionPlugin->getResourceType();

        // Assert
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
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();

        // Act
        $actualRequestBuilderPlugins = $jsonApiApiConventionPlugin->provideRequestBuilderPlugins();

        // Assert
        $this->assertEquals($this->createArrayOfRequestBuilderPluginMock(), $actualRequestBuilderPlugins);
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
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();

        // Act
        $actualRequestValidatorPlugins = $jsonApiApiConventionPlugin->provideRequestValidatorPlugins();

        // Assert
        $this->assertEquals($this->createArrayOfRequestValidatorPluginMock(), $actualRequestValidatorPlugins);
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
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();

        // Act
        $actualRequestAfterRoutingValidatorPlugins = $jsonApiApiConventionPlugin->provideRequestAfterRoutingValidatorPlugins();

        // Assert
        $this->assertEquals($this->createArrayOfRequestAfterRoutingValidatorPluginInterfaceMock(), $actualRequestAfterRoutingValidatorPlugins);
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
        $jsonApiApiConventionPlugin = $this->createJsonApiApiConventionPlugin();

        // Act
        $actualResponseFormatterPlugins = $jsonApiApiConventionPlugin->provideResponseFormatterPlugins();

        // Assert
        $this->assertEquals($this->createArrayOfResponseFormatterPluginInterfaceMock(), $actualResponseFormatterPlugins);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface
     */
    protected function createJsonApiApiConventionPlugin(): ConventionPluginInterface
    {
        return new JsonApiConventionPlugin();
    }

    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    protected function createArrayOfRequestBuilderPluginMock(): array
    {
        $firstRequestBuilderPluginInterfaceMock = $this
            ->getMockBuilder(RequestBuilderPluginInterface::class)
            ->getMock();
        $firstRequestBuilderPluginInterfaceMock->method('build')
            ->willReturn(new GlueRequestTransfer());

        $secondRequestBuilderPluginInterfaceMock = $this
            ->getMockBuilder(RequestBuilderPluginInterface::class)
            ->getMock();
        $secondRequestBuilderPluginInterfaceMock->method('build')
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
        $firstRequestValidatorPluginInterfaceMock->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        $secondRequestValidatorPluginInterfaceMock = $this
            ->getMockBuilder(RequestValidatorPluginInterface::class)
            ->getMock();
        $secondRequestValidatorPluginInterfaceMock->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(false));

        $thirdRequestValidatorPluginInterfaceMock = $this
            ->getMockBuilder(RequestValidatorPluginInterface::class)
            ->getMock();
        $thirdRequestValidatorPluginInterfaceMock->method('validate')
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
        $firstRequestAfterRoutingValidatorPluginInterfaceMock->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        $secondRequestAfterRoutingValidatorPluginInterfaceMock = $this
            ->getMockBuilder(RequestAfterRoutingValidatorPluginInterface::class)
            ->getMock();
        $secondRequestAfterRoutingValidatorPluginInterfaceMock->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(false));

        $thirdRequestAfterRoutingValidatorPluginInterfaceMock = $this
            ->getMockBuilder(RequestAfterRoutingValidatorPluginInterface::class)
            ->getMock();
        $thirdRequestAfterRoutingValidatorPluginInterfaceMock->method('validate')
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
        $responseFormatterPluginInterfaceMock->method('format')
            ->willReturn((new GlueResponseTransfer())->setContent(static::TEST_VALUE));

        return [$responseFormatterPluginInterfaceMock];
    }
}
