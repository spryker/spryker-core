<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Error\ErrorRenderer;

use Exception;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Error\ErrorConstants;
use Spryker\Shared\Error\ErrorRenderer\WebHtmlErrorRenderer;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Error
 * @group ErrorRenderer
 * @group WebErrorHtmlRendererTest
 */
class WebErrorHtmlRendererTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testWhenZedErrorPageCanRequiredRequireErrorPage()
    {
        $this->setupConfigForZedErrorPage();

        $errorPageMock = $this->getErrorPageMock('ZED');
        $errorPageMock->method('getHtmlErrorPageContent')->with(ErrorConstants::ZED_ERROR_PAGE);

        $errorPageMock->render(new Exception());
    }

    /**
     * @return void
     */
    protected function setupConfigForZedErrorPage()
    {
        $configKey = ErrorConstants::ZED_ERROR_PAGE;
        $configValue = ErrorConstants::ZED_ERROR_PAGE;

        $this->prepareConfig($configKey, $configValue);
    }

    /**
     * @return void
     */
    public function testWhenYvesErrorPageCanRequiredRequireErrorPage()
    {
        $this->setupConfigForYvesErrorPage();

        $errorPageMock = $this->getErrorPageMock('YVES');
        $errorPageMock->method('getHtmlErrorPageContent')->with(ErrorConstants::YVES_ERROR_PAGE);

        $errorPageMock->render(new \Exception());
    }

    /**
     * @return void
     */
    protected function setupConfigForYvesErrorPage()
    {
        $configKey = ErrorConstants::YVES_ERROR_PAGE;
        $configValue = ErrorConstants::YVES_ERROR_PAGE;

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
        $reflection = new \ReflectionClass(Config::class);
        $reflectionProperty = $reflection->getProperty('config');
        $reflectionProperty->setAccessible(true);

        $config = $reflectionProperty->getValue();
        $config[$configKey] = $configValue;

        $reflectionProperty->setValue($config);
    }

    /**
     * @param string $application
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Error\ErrorRenderer\ErrorRendererInterface
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
