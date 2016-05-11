<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Application\Business\Model\Request;

use PHPUnit_Framework_MockObject_MockObject;
use Spryker\Zed\Application\Business\Exception\UrlInvalidException;
use Spryker\Zed\Application\Business\Model\Request\SubRequestHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @group Spryker
 * @group Zed
 * @group Application
 * @group Business
 * @group SubRequestHandler
 */
class SubRequestHandlerTest extends \PHPUnit_Framework_TestCase
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

        $httpKernelMock = $this->getMock(HttpKernelInterface::class, ['handle']);
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
     * @expectedException \Spryker\Zed\Application\Business\Exception\UrlInvalidException
     * @return void
     */
    public function testInvalidUrlThrowsUrlInvalidException()
    {
        $mainRequest = new Request();
        $subRequest = new Request();

        $httpKernelMock = $this->getMock(HttpKernelInterface::class, ['handle']);

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
     * @return \PHPUnit_Framework_MockObject_MockObject | SubRequestHandler
     */
    private function getRequestHandlerPartialMock(PHPUnit_Framework_MockObject_MockObject $kernelMock)
    {
        return $this->getMock(SubRequestHandler::class, ['createRequestObject'], [$kernelMock]);
    }

}
