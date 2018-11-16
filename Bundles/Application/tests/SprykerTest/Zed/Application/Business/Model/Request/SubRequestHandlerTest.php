<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Application\Business\Model\Request;

use Silex\Application;
use Silex\WebTestCase;
use Spryker\Zed\Application\Business\Model\Request\SubRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Application
 * @group Business
 * @group Model
 * @group Request
 * @group SubRequestHandlerTest
 * Add your own group annotations below this line
 */
class SubRequestHandlerTest extends WebTestCase
{
    public const GET_PARAMS = ['banana', 'mango'];
    public const POST_PARAMS = ['apple', 'orange'];
    public const URL_MASTER_REQUEST = '/';
    public const URL_SUB_REQUEST = '/sales/comment/add';

    /**
     * @return void
     */
    public function setUp()
    {
        Request::setTrustedHosts([]);
        Request::setTrustedProxies([]);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testHandleSubRequestWithGetParams()
    {
        $client = $this->createClient();
        $client->request('get', self::URL_MASTER_REQUEST, self::GET_PARAMS);
        $this->assertInstanceOf(RedirectResponse::class, $client->getResponse());
    }

    /**
     * @return void
     */
    public function testHandleSubRequestWithPostParams()
    {
        $client = $this->createClient();
        $client->request('post', self::URL_MASTER_REQUEST, self::POST_PARAMS);
        $this->assertInstanceOf(RedirectResponse::class, $client->getResponse());
    }

    /**
     * @return \Silex\Application
     */
    public function createApplication()
    {
        $app = new Application();
        $app['debug'] = true;

        $callback = function () use ($app) {
            $subRequestHandler = new SubRequestHandler($app);
            return $subRequestHandler->handleSubRequest(new Request(), self::URL_SUB_REQUEST);
        };

        $app->get(self::URL_MASTER_REQUEST, $callback);
        $app->post(self::URL_MASTER_REQUEST, $callback);
        $app->get(self::URL_SUB_REQUEST, function () use ($app) {
            return new RedirectResponse(self::URL_SUB_REQUEST);
        });

        return $app;
    }
}
