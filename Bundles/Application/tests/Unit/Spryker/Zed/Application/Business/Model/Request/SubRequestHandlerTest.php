<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Application\Business\Model\Request;

use Silex\Application;
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

        $subRequestHandlerPartialMock = $this->getMock(SubRequestHandler::class, ['createRequestObject'], [$httpKernelMock]);
        $subRequestHandlerPartialMock
            ->expects($this->once())
            ->method('createRequestObject')
            ->willReturn($subRequest);

        $subRequestHandlerPartialMock->handleSubRequest($mainRequest, self::URL_SUB_REQUEST);

        $this->assertEquals($subRequest->query->all(), self::GET_PARAMS);
        $this->assertEquals($subRequest->request->all(), self::POST_PARAMS);
    }
}
