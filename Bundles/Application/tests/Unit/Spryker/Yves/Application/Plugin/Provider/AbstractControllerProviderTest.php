<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\ApplicationPlugin\Provider;

use Silex\Application;
use Silex\Controller;
use Unit\Spryker\Yves\Application\Plugin\Provider\Fixtures\ControllerProviderMock;

/**
 * @group Abstract
 * @group Controller
 * @group Yves
 * @group Ssl
 */
class AbstractControllerProviderTest extends \PHPUnit_Framework_TestCase
{

    const METHOD_REQUIRE_HTTP = 'requireHttp';
    const METHOD_REQUIRE_HTTPS = 'requireHttps';

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
    public function testIsControllerSslEnabled()
    {
        $this->markTestIncomplete('Complete this test');
//        $app = new Application();
//        $controllerMock = $this->getControllerMock('requireHttps');
//        $controllerProviderMock = $this->createControllerProviderMock(true, $controllerMock);
//        $controllerProviderMock->defineControllers($app);
    }

    /**
     * @return void
     */
    public function testIsControllerSslEnabledWithExcludedUrl()
    {
        $this->markTestIncomplete('Complete this test');
//        $app = new Application();
//        $controllerMock = $this->createControllerProviderMock(true);
//        $controllerMock
//            ->expects($this->once())
//            ->method('getExcludedUrls')
//            ->willReturn([
//                'foo' => '/foo'
//            ]);
    }

    /**
     * @param bool $ssl
     * @param \Silex\Controller $controller
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Unit\Spryker\Yves\Application\Plugin\Provider\Fixtures\ControllerProviderMock
     */
    protected function createControllerProviderMock($ssl, $controller)
    {
        $controllerProviderMock = $this->getMock(ControllerProviderMock::class, ['getService', 'getController'], [$ssl]);
        $controllerProviderMock->method('getService')->willReturn('');
        $controllerProviderMock->method('getController')->willReturn($controller);

        return $controllerProviderMock;
    }

    /**
     * @param string $methodName
     * @param \PHPUnit_Framework_MockObject_Matcher_InvokedCount $callTimes
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Silex\Controller
     */
    private function getControllerMock($methodName, $callTimes)
    {
        $controllerMock = $this->getMock(Controller::class, [], [], '', false);
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
