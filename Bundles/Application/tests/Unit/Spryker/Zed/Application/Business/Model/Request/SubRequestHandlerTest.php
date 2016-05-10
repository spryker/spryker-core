<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Application\Business\Model\Request;

use Codeception\Util\Stub;
use Spryker\Zed\Application\Business\Model\Request\SubRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Tests\Fixtures\KernelForTest;

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
    const ADDITIONAL_PARAMS = [];
    const URL = '/sales/comment/add';

    public function testHandleSubRequest()
    {
        $request = new Request();
        $request->query->add(self::GET_PARAMS);
        $request->request->add(self::POST_PARAMS);
        $httpKernelMock = $this->getMock(HttpKernelInterface::class, ['handle']);
        $httpKernelMock->method('handle')->willReturn($request);
        $subRequestHandler = new SubRequestHandler($httpKernelMock);
        $changedRequest = $subRequestHandler->handleSubRequest($request, self::URL);

        $this->assertEquals(self::GET_PARAMS, $changedRequest->query->all());
    }

}
