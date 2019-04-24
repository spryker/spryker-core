<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ErrorHandler;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Shared\Config\Config;
use Spryker\Shared\ErrorHandler\ErrorHandler;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Shared\ErrorHandler\ErrorHandlerFactory;
use Spryker\Shared\ErrorHandler\ErrorRenderer\CliErrorRenderer;
use Spryker\Shared\ErrorHandler\ErrorRenderer\WebExceptionErrorRenderer;
use Spryker\Shared\ErrorHandler\ErrorRenderer\WebHtmlErrorRenderer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group ErrorHandler
 * @group ErrorHandlerFactoryTest
 * Add your own group annotations below this line
 */
class ErrorHandlerFactoryTest extends Unit
{
    /**
     * @var array
     */
    protected $configCache;

    /**
     * @return void
     */
    public function setUp()
    {
        $reflectionProperty = $this->getConfigReflectionProperty();
        $this->configCache = $reflectionProperty->getValue();
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        $reflectionProperty = $this->getConfigReflectionProperty();
        $reflectionProperty->setValue($this->configCache);
    }

    /**
     * @return \ReflectionProperty
     */
    protected function getConfigReflectionProperty()
    {
        $reflection = new ReflectionClass(Config::class);
        $reflectionProperty = $reflection->getProperty('config');
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty;
    }

    /**
     * @return void
     */
    public function testCreateErrorHandlerShouldReturnErrorHandlerWithCliErrorRendererWhenSapiIsCli()
    {
        $errorHandlerFactoryMock = $this->getErrorHandlerFactoryMock('ZED', ['isCliCall', 'createCliRenderer']);
        $errorHandlerFactoryMock->expects($this->once())->method('isCliCall')->willReturn(true);
        $errorHandlerFactoryMock->expects($this->once())->method('createCliRenderer')->willReturn(new CliErrorRenderer());

        $errorHandler = $errorHandlerFactoryMock->createErrorHandler();
        $this->assertInstanceOf(ErrorHandler::class, $errorHandler);
    }

    /**
     * @return void
     */
    public function testCreateErrorHandlerShouldReturnErrorHandlerWithWebHtmlErrorRendererAsDefaultWhenSapiNotCliAndNoConfigGiven()
    {
        $errorHandlerFactoryMock = $this->getErrorHandlerFactoryMock('ZED', ['isCliCall', 'createWebErrorRenderer']);
        $errorHandlerFactoryMock->expects($this->once())->method('isCliCall')->willReturn(false);
        $errorHandlerFactoryMock->expects($this->once())->method('createWebErrorRenderer')
            ->with(WebHtmlErrorRenderer::class)
            ->willReturn(new WebHtmlErrorRenderer('ZED'));

        $this->unsetAllErrorRelatedConfigs();

        $errorHandler = $errorHandlerFactoryMock->createErrorHandler();
        $this->assertInstanceOf(ErrorHandler::class, $errorHandler);
    }

    /**
     * @return void
     */
    public function testCreateErrorHandlerShouldReturnErrorHandlerWithConfiguredWebExceptionErrorRendererWhenSapiNotCliAndErrorRendererConfigGiven()
    {
        $errorHandlerFactoryMock = $this->getErrorHandlerFactoryMock('ZED', ['isCliCall', 'createWebErrorRenderer']);
        $errorHandlerFactoryMock->expects($this->once())->method('isCliCall')->willReturn(false);
        $errorHandlerFactoryMock->expects($this->once())->method('createWebErrorRenderer')
            ->with(WebExceptionErrorRenderer::class)
            ->willReturn(new WebExceptionErrorRenderer());

        $this->unsetAllErrorRelatedConfigs();
        $configProperty = $this->getConfigReflectionProperty();
        $config = $configProperty->getValue();
        $config[ErrorHandlerConstants::ERROR_RENDERER] = WebExceptionErrorRenderer::class;
        $configProperty->setValue($config);

        $errorHandler = $errorHandlerFactoryMock->createErrorHandler();
        $this->assertInstanceOf(ErrorHandler::class, $errorHandler);
    }

    /**
     * @return void
     */
    public function testCreateErrorHandlerShouldReturnErrorHandlerWithConfiguredWebHtmlErrorRendererWhenSapiNotCliAndErrorRendererConfigGiven()
    {
        $errorHandlerFactoryMock = $this->getErrorHandlerFactoryMock('ZED', ['isCliCall', 'createWebErrorRenderer']);
        $errorHandlerFactoryMock->expects($this->once())->method('isCliCall')->willReturn(false);
        $errorHandlerFactoryMock->expects($this->once())->method('createWebErrorRenderer')
            ->with(WebHtmlErrorRenderer::class)
            ->willReturn(new WebHtmlErrorRenderer('ZED'));

        $this->unsetAllErrorRelatedConfigs();
        $configProperty = $this->getConfigReflectionProperty();
        $config = $configProperty->getValue();
        $config[ErrorHandlerConstants::ERROR_RENDERER] = WebHtmlErrorRenderer::class;
        $configProperty->setValue($config);

        $errorHandler = $errorHandlerFactoryMock->createErrorHandler();
        $this->assertInstanceOf(ErrorHandler::class, $errorHandler);
    }

    /**
     * @param string $application
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\ErrorHandler\ErrorHandlerFactory
     */
    protected function getErrorHandlerFactoryMock($application, array $methods)
    {
        return $this->getMockBuilder(ErrorHandlerFactory::class)
            ->setMethods($methods)
            ->setConstructorArgs([$application])
            ->getMock();
    }

    /**
     * @return void
     */
    protected function unsetAllErrorRelatedConfigs()
    {
        $configProperty = $this->getConfigReflectionProperty();
        $config = $configProperty->getValue();
        if (isset($config[ErrorHandlerConstants::ERROR_RENDERER])) {
            unset($config[ErrorHandlerConstants::ERROR_RENDERER]);
        }

        $configProperty->setValue($config);
    }
}
