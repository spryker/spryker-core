<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Application\Business\Model\Request;

use Silex\Application;
use Silex\WebTestCase;
use Spryker\Zed\Application\Business\Model\Request\SubRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Client;

/**
 * @group Spryker
 * @group Zed
 * @group Application
 * @group Business
 * @group SubRequestHandler
 */
class SubRequestHandlerTest extends WebTestCase
{
    const GET_PARAMS = ['banana', 'mango'];
    const POST_PARAMS = ['apple', 'orange'];
    const URL_MASTER_REQUEST = '/';
    const URL_SUB_REQUEST = '/sales/comment/add';

    public function testHandleSubRequestWithGetParams()
    {
        $app = $this->createApplication();
        $client = new Client($app);
        $client->request('get', self::URL_MASTER_REQUEST, self::GET_PARAMS);
        $this->assertTrue($client->getResponse() instanceof RedirectResponse);
    }

    public function testHandleSubRequestWithPostParams()
    {
        $app = $this->createApplication();
        $client = new Client($app);
        $client->request('post', self::URL_MASTER_REQUEST, self::POST_PARAMS);
        $this->assertTrue($client->getResponse() instanceof RedirectResponse);
    }

    public function createApplication($authenticationMethod = 'form')
    {
        $app = new Application();
        $app['debug'] = true;

        $app->get(self::URL_MASTER_REQUEST, function () use ($app) {
            $subRequestHandler = new SubRequestHandler($app);
            return $subRequestHandler->handleSubRequest(new Request(), self::URL_SUB_REQUEST);
        });

        $app->post(self::URL_MASTER_REQUEST, function () use ($app) {
            $subRequestHandler = new SubRequestHandler($app);
            return $subRequestHandler->handleSubRequest(new Request(), self::URL_SUB_REQUEST);
        });

        $app->get(self::URL_SUB_REQUEST, function () use ($app) {
            return new RedirectResponse(self::URL_SUB_REQUEST);
        });

        return $app;
    }
}
