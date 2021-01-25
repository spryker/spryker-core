<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application\EventListener;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;
use Spryker\Shared\Application\EventListener\KernelLogListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Application
 * @group EventListener
 * @group KernelLogListenerTest
 * Add your own group annotations below this line
 */
class KernelLogListenerTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateInstance(): void
    {
        $loggerMock = $this->getLoggerMock();
        $kernelLogListener = new KernelLogListener($loggerMock);

        $this->assertInstanceOf(EventSubscriberInterface::class, $kernelLogListener);
    }

    /**
     * @return void
     */
    public function testOnKernelRequestLogRequestShouldCalledWhenMasterRequest(): void
    {
        $requestMock = $this->getRequestMock();
        $requestMock->expects($this->once())->method('getRequestUri')->willReturn('/foo/bar');
        $requestMock->expects($this->once())->method('getMethod')->willReturn(Request::METHOD_GET);

        $event = new RequestEvent(
            $this->getKernelMock(),
            $requestMock,
            HttpKernelInterface::MASTER_REQUEST
        );

        $loggerMock = $this->getLoggerMock();
        $loggerMock->expects($this->once())->method('info');

        $kernelLogListener = new KernelLogListener($loggerMock);
        $kernelLogListener->onKernelRequest($event);
    }

    /**
     * @return void
     */
    public function testOnKernelRequestLogRequestShouldNotCalledWhenNotMasterRequest(): void
    {
        $event = new RequestEvent(
            $this->getKernelMock(),
            Request::createFromGlobals(),
            HttpKernelInterface::SUB_REQUEST
        );

        $loggerMock = $this->getLoggerMock();
        $loggerMock->expects($this->never())->method('info');

        $kernelLogListener = new KernelLogListener($loggerMock);
        $kernelLogListener->onKernelRequest($event);
    }

    /**
     * @return void
     */
    public function testOnKernelResponseLogResponseShouldCalledWhenMasterRequest(): void
    {
        $responseMock = $this->responseMock();
        $responseMock->expects($this->once())->method('getStatusCode');

        $event = new ResponseEvent(
            $this->getKernelMock(),
            $this->getRequestMock(),
            HttpKernelInterface::MASTER_REQUEST,
            $responseMock
        );

        $loggerMock = $this->getLoggerMock();
        $loggerMock->expects($this->once())->method('info');

        $kernelLogListener = new KernelLogListener($loggerMock);
        $kernelLogListener->onKernelResponse($event);
    }

    /**
     * @return void
     */
    public function testOnKernelResponseLogResponseShouldNotCalledWhenNotMasterRequest(): void
    {
        $event = new ResponseEvent(
            $this->getKernelMock(),
            $this->getRequestMock(),
            HttpKernelInterface::SUB_REQUEST,
            $this->responseMock()
        );

        $loggerMock = $this->getLoggerMock();
        $loggerMock->expects($this->never())->method('info');

        $kernelLogListener = new KernelLogListener($loggerMock);
        $kernelLogListener->onKernelResponse($event);
    }

    /**
     * @return void
     */
    public function testOnKernelResponseLogResponseWithRedirectResponseShouldCalledWhenMasterRequest(): void
    {
        $responseMock = $this->redirectResponseMock();
        $responseMock->expects($this->once())->method('getStatusCode');
        $responseMock->expects($this->once())->method('getTargetUrl');

        $event = new ResponseEvent(
            $this->getKernelMock(),
            $this->getRequestMock(),
            HttpKernelInterface::MASTER_REQUEST,
            $responseMock
        );

        $loggerMock = $this->getLoggerMock();
        $loggerMock->expects($this->once())->method('info');

        $kernelLogListener = new KernelLogListener($loggerMock);
        $kernelLogListener->onKernelResponse($event);
    }

    /**
     * @return void
     */
    public function testGetSubscribedEventsShouldReturnArray(): void
    {
        $loggerMock = $this->getLoggerMock();
        $kernelLogListener = new KernelLogListener($loggerMock);

        $this->assertIsArray($kernelLogListener->getSubscribedEvents());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Psr\Log\LoggerInterface
     */
    protected function getLoggerMock(): LoggerInterface
    {
        $loggerInterfaceMock = $this->getMockBuilder(LoggerInterface::class)->getMock();

        return $loggerInterfaceMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected function getKernelMock(): HttpKernelInterface
    {
        $httpKernelInterfaceMock = $this->getMockBuilder(HttpKernelInterface::class)->getMock();

        return $httpKernelInterfaceMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Request
     */
    protected function getRequestMock(): Request
    {
        $requestMock = $this->getMockBuilder(Request::class)->getMock();

        return $requestMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Response
     */
    protected function responseMock(): Response
    {
        $responseMock = $this->getMockBuilder(Response::class)->getMock();

        return $responseMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectResponseMock(): RedirectResponse
    {
        $redirectResponseMock = $this->getMockBuilder(RedirectResponse::class)->disableOriginalConstructor()->getMock();

        return $redirectResponseMock;
    }
}
