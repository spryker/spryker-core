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
use Spryker\Yves\ZedRequest\Plugin\ZedResponseLogPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group ZedRequest
 * @group Plugin
 * @group ZedResponseLogPluginTest
 * Add your own group annotations below this line
 */
class ZedResponseLogPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testGetNameShouldReturnString()
    {
        $zedResponseLogPlugin = new ZedResponseLogPlugin();

        $this->assertIsString($zedResponseLogPlugin->getName());
    }

    /**
     * @return void
     */
    public function testGetCallableShouldReturnCallable()
    {
        $zedResponseLogPlugin = new ZedResponseLogPlugin();

        $this->assertIsCallable($zedResponseLogPlugin->getCallable());
    }

    /**
     * @return void
     */
    public function testExecuteCallableShouldCallLogger()
    {
        $this->markTestIncomplete('test response logging incomplete');
        $loggerMock = $this->getLoggerMock();
        $loggerMock->expects($this->once())->method('info');

        $zedResponseLogPluginMock = $this->getZedResponseLogPluginMock($loggerMock);

        $handlerStack = HandlerStack::create();
        $handlerStack->push($zedResponseLogPluginMock->getCallable(), $zedResponseLogPluginMock->getName());
        $handler = $handlerStack->resolve();
        $request = new Request('POST', 'www.example.com');
        $handler($request, []);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Psr\Log\LoggerInterface
     */
    protected function getLoggerMock()
    {
        $lockerMock = $this->getMockBuilder(LoggerInterface::class)->getMock();

        return $lockerMock;
    }

    /**
     * @param \Psr\Log\LoggerInterface $loggerMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\ZedRequest\Plugin\ZedResponseLogPlugin
     */
    protected function getZedResponseLogPluginMock(LoggerInterface $loggerMock)
    {
        $zedResponseLogPluginMock = $this->getMockBuilder(ZedResponseLogPlugin::class)
            ->setMethods(['getLogger'])
            ->getMock();

        $zedResponseLogPluginMock->expects($this->once())->method('getLogger')->willReturn($loggerMock);

        return $zedResponseLogPluginMock;
    }
}
