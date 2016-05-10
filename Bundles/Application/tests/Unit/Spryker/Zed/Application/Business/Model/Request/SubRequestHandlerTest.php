<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Application\Business\Model\Request;

use Codeception\Util\Stub;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
    const URL = '/sales/comment/add';

    public function testHandleSubRequest()
    {
        $request = new Request();
        $request->query->add(self::GET_PARAMS);
        $request->request->add(self::POST_PARAMS);

        $response = new Response();

        $subRequestHandler = Stub::make('Spryker\Zed\Application\Business\Model\Request\SubRequestHandler', ['handleSubRequest' => $response]);
        $response = $subRequestHandler->handleSubRequest($request, self::URL);
        $this->assertTrue($response instanceof Response);
    }

}
