<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Plugin\Provider;

use Silex\Application;
use Spryker\Yves\Application\Plugin\Provider\YvesControllerProvider;
use Symfony\Component\HttpFoundation\Request;
use Unit\Spryker\Yves\Plugin\Provider\Fixtures\ControllerProviderMockTest;

/**
 * @group Abstract
 * @group Controller
 * @group Yves
 * @group Ssl
 */
class AbstractControllerProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testIsControllerSslIsNull()
    {
        $app = new Application();
        $controllerMock = $this->createControllerProviderMock(false);
        $controllerMock
            ->expects($this->once())
            ->method('__call')
            ->with(
                $this->equalTo('requireHttps'),
                $this->equalTo(['123'])
            );

        $app->mount($controllerMock->getUrlPrefix(), $controllerMock);

        $request = Request::create('/foo');
        $response = $app->handle($request);

        $this->assertNotEquals(301, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testIsControllerSslEnabled()
    {
        $app = new Application();
        $controllerMock = $this->createControllerProviderMock(true);

        $app->mount($controllerMock->getUrlPrefix(), $controllerMock);

        $request = Request::create('/foo');
        $response = $app->handle($request);

        $this->assertEquals(301, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testIsControllerSslEnabledWithExcludedUrl()
    {
        $app = new Application();
        $controllerMock = $this->createControllerProviderMock(true);
        $controllerMock
            ->expects($this->once())
            ->method('getExcludedUrls')
            ->willReturn([
                'foo' => '/foo'
            ]);

        $app->mount($controllerMock->getUrlPrefix(), $controllerMock);

        $request = Request::create('/foo');
        $response = $app->handle($request);

        $this->assertNotEquals(301, $response->getStatusCode());
    }

    /**
     * @param bool $ssl
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Unit\Spryker\Yves\Plugin\Provider\Fixtures\ControllerProviderMockTest
     */
    protected function createControllerProviderMock($ssl)
    {
        return $this->getMock(ControllerProviderMockTest::class, ['getExcludedUrls']);
    }

}
