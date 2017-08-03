<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\ZedRequest\Plugin;

use Codeception\Test\Unit;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Psr\Log\LoggerInterface;
use Spryker\Shared\ZedRequest\Client\AbstractHttpClient;
use Spryker\Yves\ZedRequest\Plugin\ZedRequestLogPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group ZedRequest
 * @group Plugin
 * @group ZedRequestLogPluginTest
 * Add your own group annotations below this line
 */
class ZedRequestLogPluginTest extends Unit
{

    /**
     * @return void
     */
    public function testGetNameShouldReturnString()
    {
        $zedRequestLogPlugin = new ZedRequestLogPlugin();

        $this->assertInternalType('string', $zedRequestLogPlugin->getName());
    }

    /**
     * @return void
     */
    public function testGetCallableShouldReturnCallable()
    {
        $zedRequestLogPlugin = new ZedRequestLogPlugin();

        $this->assertInternalType('callable', $zedRequestLogPlugin->getCallable());
    }

    /**
     * @return void
     */
    public function testExecuteCallableShouldCallLogger()
    {
        $loggerMock = $this->getLoggerMock();
        $loggerMock->expects($this->once())->method('info');

        $zedRequestLogPluginMock = $this->getZedRequestLogPluginMock($loggerMock);

        $handlerStack = HandlerStack::create();
        $handlerStack->push($zedRequestLogPluginMock->getCallable(), $zedRequestLogPluginMock->getName());
        $handler = $handlerStack->resolve();
        $request = new Request('POST', 'www.example.com');
        $request = $request->withHeader(AbstractHttpClient::HEADER_HOST_YVES, 1);
        $handler($request, []);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Psr\Log\LoggerInterface
     */
    protected function getLoggerMock()
    {
        $lockerMock = $this->getMockBuilder(LoggerInterface::class)->getMock();

        return $lockerMock;
    }

    /**
     * @param \Psr\Log\LoggerInterface $loggerMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Yves\ZedRequest\Plugin\ZedRequestLogPlugin
     */
    protected function getZedRequestLogPluginMock(LoggerInterface $loggerMock)
    {
        $zedRequestLogPluginMock = $this->getMockBuilder(ZedRequestLogPlugin::class)
            ->setMethods(['getLogger'])
            ->getMock();

        $zedRequestLogPluginMock->expects($this->once())->method('getLogger')->willReturn($loggerMock);

        return $zedRequestLogPluginMock;
    }

}
