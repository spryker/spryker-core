<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Http\Communication\SubRequest;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Communication\ApplicationProxy;
use Spryker\Zed\Http\Communication\SubRequest\SubRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Client;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Http
 * @group Communication
 * @group SubRequest
 * @group SubRequestHandlerTest
 * Add your own group annotations below this line
 */
class SubRequestHandlerTest extends Unit
{
    public const GET_PARAMS = ['banana', 'mango'];
    public const POST_PARAMS = ['apple', 'orange'];
    public const URL_MASTER_REQUEST = '/';
    public const URL_SUB_REQUEST = '/sales/comment/add';

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        Request::setTrustedHosts([]);
        Request::setTrustedProxies([], Request::HEADER_X_FORWARDED_ALL);
    }

    /**
     * @return void
     */
    public function testHandleSubRequestWithGetParams(): void
    {
        $client = $this->createClient();
        $client->request('get', static::URL_MASTER_REQUEST, self::GET_PARAMS);
        $this->assertInstanceOf(RedirectResponse::class, $client->getResponse());
    }

    /**
     * @return void
     */
    public function testHandleSubRequestWithPostParams(): void
    {
        $client = $this->createClient();
        $client->request('post', static::URL_MASTER_REQUEST, static::POST_PARAMS);
        $this->assertInstanceOf(RedirectResponse::class, $client->getResponse());
    }

    /**
     * @deprecated This can be refactored to use the ApplicationHelper which will be released together with the SecurityApplicationPlugin.
     *
     * @return \Spryker\Shared\Kernel\Communication\ApplicationProxy
     */
    public function createApplication(): ApplicationProxy
    {
        $app = new ApplicationProxy();
        $app['debug'] = true;

        $callback = function () use ($app) {
            $subRequestHandler = new SubRequestHandler($app);

            return $subRequestHandler->handleSubRequest(new Request(), self::URL_SUB_REQUEST);
        };

        $app['controllers']->get(static::URL_MASTER_REQUEST, $callback);
        $app->post(static::URL_MASTER_REQUEST, $callback);
        $app['controllers']->get(static::URL_SUB_REQUEST, function () {
            return new RedirectResponse(static::URL_SUB_REQUEST);
        });

        return $app;
    }

    /**
     * @param array $server
     *
     * @return \Symfony\Component\HttpKernel\Client
     */
    protected function createClient(array $server = []): Client
    {
        return new Client($this->createApplication(), $server);
    }
}
