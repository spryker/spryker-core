<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Application\Plugin\Provider;

use Codeception\Test\Unit;
use Silex\Application;
use Silex\Controller;
use SprykerTest\Yves\Application\Plugin\Provider\Fixtures\ControllerProviderMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Application
 * @group Plugin
 * @group Provider
 * @group YvesControllerProviderTest
 * Add your own group annotations below this line
 */
class YvesControllerProviderTest extends Unit
{
    public const METHOD_REQUIRE_HTTP = 'requireHttp';
    public const METHOD_REQUIRE_HTTPS = 'requireHttps';

    /**
     * @return void
     */
    public function testWithoutSslConfigurationRequireHttpIsNotCalled()
    {
        $app = new Application();
        $controllerMock = $this->getControllerMock(self::METHOD_REQUIRE_HTTP, $this->never());
        $controllerProviderMock = $this->createControllerProviderMock(null, $controllerMock);
        $controllerProviderMock->defineControllers($app);
    }

    /**
     * @return void
     */
    public function testWithoutSslConfigurationRequireHttpsIsNotCalled()
    {
        $app = new Application();
        $controllerMock = $this->getControllerMock(self::METHOD_REQUIRE_HTTPS, $this->never());
        $controllerProviderMock = $this->createControllerProviderMock(null, $controllerMock);
        $controllerProviderMock->defineControllers($app);
    }

    /**
     * @return void
     */
    public function testWhenSslEnabledFalseRequireHttpIsCalled()
    {
        $app = new Application();
        $controllerMock = $this->getControllerMock(self::METHOD_REQUIRE_HTTP, $this->once());
        $controllerProviderMock = $this->createControllerProviderMock(false, $controllerMock);
        $controllerProviderMock->defineControllers($app);
    }

    /**
     * @return void
     */
    public function testWhenSslEnabledTrueRequireHttpsIsCalled()
    {
        $app = new Application();
        $controllerMock = $this->getControllerMock(self::METHOD_REQUIRE_HTTPS, $this->once());
        $controllerProviderMock = $this->createControllerProviderMock(true, $controllerMock);
        $controllerProviderMock->defineControllers($app);
    }

    /**
     * @return void
     */
    public function testWhenSslEnabledTrueRequireHttpsWithExcludedUrlIsNotCalled()
    {
        $app = new Application();
        $controllerMock = $this->getControllerMock(self::METHOD_REQUIRE_HTTPS, $this->never());
        $controllerProviderMock = $this->createControllerProviderMock(true, $controllerMock, ['foo' => '/foo']);
        $controllerProviderMock->defineControllers($app);
    }

    /**
     * @param bool $ssl
     * @param \Silex\Controller $controller
     * @param array $urls
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Unit\Spryker\Yves\Application\Plugin\Provider\Fixtures\ControllerProviderMock
     */
    protected function createControllerProviderMock($ssl, $controller, array $urls = [])
    {
        $controllerProviderMock = $this->getMockBuilder(ControllerProviderMock::class)->setMethods(['getService', 'getController', 'getExcludedUrls'])->setConstructorArgs([$ssl])->getMock();
        $controllerProviderMock->method('getService')->willReturn('');
        $controllerProviderMock->method('getController')->willReturn($controller);
        $controllerProviderMock->method('getExcludedUrls')->willReturn($urls);

        return $controllerProviderMock;
    }

    /**
     * @param string $methodName
     * @param \PHPUnit\Framework\MockObject\Matcher\InvokedCount $callTimes
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Silex\Controller
     */
    private function getControllerMock($methodName, $callTimes)
    {
        $controllerMock = $this->getMockBuilder(Controller::class)->disableOriginalConstructor()->getMock();
        $controllerMock
            ->expects($callTimes)
            ->method('__call')
            ->with(
                $this->equalTo($methodName),
                $this->equalTo([])
            );

        return $controllerMock;
    }
}
