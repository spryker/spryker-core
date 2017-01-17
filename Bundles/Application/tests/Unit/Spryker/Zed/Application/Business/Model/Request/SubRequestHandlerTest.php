<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Application\Business\Model\Request;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\Application\Business\Model\Request\SubRequestHandler;
use Spryker\Zed\Url\Business\Exception\UrlInvalidException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Application
 * @group Business
 * @group Model
 * @group Request
 * @group SubRequestHandlerTest
 */
class SubRequestHandlerTest extends PHPUnit_Framework_TestCase
{

    const GET_PARAMS = ['banana', 'mango'];
    const POST_PARAMS = ['apple', 'orange'];
    const URL_SUB_REQUEST = '/sales/comment/add';
    const INCORRECT_URL = '/sales/comment';

    /**
     * @return void
     */
    public function testSubRequestIsSetupCorrectly()
    {
        $mainRequest = new Request();
        $mainRequest->query->add(self::GET_PARAMS);
        $mainRequest->request->add(self::POST_PARAMS);
        $subRequest = new Request();

        $httpKernelMock = $this->getMockBuilder(HttpKernelInterface::class)->setMethods(['handle'])->getMock();
        $httpKernelMock
            ->expects($this->once())
            ->method('handle')
            ->with($subRequest, HttpKernelInterface::SUB_REQUEST, true);

        $subRequestHandlerPartialMock = $this->getRequestHandlerPartialMock($httpKernelMock);
        $subRequestHandlerPartialMock
            ->expects($this->once())
            ->method('createRequestObject')
            ->willReturn($subRequest);

        $subRequestHandlerPartialMock->handleSubRequest($mainRequest, self::URL_SUB_REQUEST);

        $this->assertEquals($subRequest->query->all(), self::GET_PARAMS);
        $this->assertEquals($subRequest->request->all(), self::POST_PARAMS);
    }

    /**
     * @expectedException \Spryker\Zed\Url\Business\Exception\UrlInvalidException
     *
     * @return void
     */
    public function testInvalidUrlThrowsUrlInvalidException()
    {
        $mainRequest = new Request();
        $subRequest = new Request();

        $httpKernelMock = $this->getMockBuilder(HttpKernelInterface::class)->setMethods(['handle'])->getMock();

        $subRequestHandlerPartialMock = $this->getRequestHandlerPartialMock($httpKernelMock);
        $subRequestHandlerPartialMock
            ->expects($this->once())
            ->method('createRequestObject')
            ->willReturn($subRequest);

        $subRequestHandlerPartialMock->handleSubRequest($mainRequest, self::INCORRECT_URL);
        $this->expectException(UrlInvalidException::class);
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $kernelMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject | SubRequestHandler
     */
    private function getRequestHandlerPartialMock(PHPUnit_Framework_MockObject_MockObject $kernelMock)
    {
        return $this->getMockBuilder(SubRequestHandler::class)->setMethods(['createRequestObject'])->setConstructorArgs([$kernelMock])->getMock();
    }

}
