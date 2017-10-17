<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ErrorHandler\ErrorRenderer;

use Codeception\Test\Unit;
use Exception;
use ReflectionClass;
use Spryker\Shared\Config\Config;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Shared\ErrorHandler\ErrorRenderer\WebHtmlErrorRenderer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group ErrorHandler
 * @group ErrorRenderer
 * @group WebErrorHtmlRendererTest
 * Add your own group annotations below this line
 */
class WebErrorHtmlRendererTest extends Unit
{
    /**
     * @return void
     */
    public function testWhenZedErrorPageCanRequiredRequireErrorPage()
    {
        $this->setupConfigForZedErrorPage();

        $errorPageMock = $this->getErrorPageMock('ZED');
        $errorPageMock->method('getHtmlErrorPageContent')->with(ErrorHandlerConstants::ZED_ERROR_PAGE);

        $errorPageMock->render(new Exception());
    }

    /**
     * @return void
     */
    protected function setupConfigForZedErrorPage()
    {
        $configKey = ErrorHandlerConstants::ZED_ERROR_PAGE;
        $configValue = ErrorHandlerConstants::ZED_ERROR_PAGE;

        $this->prepareConfig($configKey, $configValue);
    }

    /**
     * @return void
     */
    public function testWhenYvesErrorPageCanRequiredRequireErrorPage()
    {
        $this->setupConfigForYvesErrorPage();

        $errorPageMock = $this->getErrorPageMock('YVES');
        $errorPageMock->method('getHtmlErrorPageContent')->with(ErrorHandlerConstants::YVES_ERROR_PAGE);

        $errorPageMock->render(new Exception());
    }

    /**
     * @return void
     */
    protected function setupConfigForYvesErrorPage()
    {
        $configKey = ErrorHandlerConstants::YVES_ERROR_PAGE;
        $configValue = ErrorHandlerConstants::YVES_ERROR_PAGE;

        $this->prepareConfig($configKey, $configValue);
    }

    /**
     * @param string $configKey
     * @param string $configValue
     *
     * @return void
     */
    protected function prepareConfig($configKey, $configValue)
    {
        $reflection = new ReflectionClass(Config::class);
        $reflectionProperty = $reflection->getProperty('config');
        $reflectionProperty->setAccessible(true);

        $config = $reflectionProperty->getValue();
        $config[$configKey] = $configValue;

        $reflectionProperty->setValue($config);
    }

    /**
     * @param string $application
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\ErrorHandler\ErrorRenderer\ErrorRendererInterface
     */
    protected function getErrorPageMock($application)
    {
        $errorPageMock = $this->getMockBuilder(WebHtmlErrorRenderer::class)
            ->setMethods(['getHtmlErrorPageContent'])
            ->setConstructorArgs([$application])
            ->getMock();

        return $errorPageMock;
    }
}
