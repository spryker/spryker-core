<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Http\Communication\SubRequest;

use Codeception\Test\Unit;
use Spryker\Zed\Http\Communication\SubRequest\SubRequestHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
    public const GET_PARAMS = ['fruit' => 'mango'];
    public const POST_PARAMS = ['fruit' => 'orange'];

    public const URL_SUB_REQUEST = '/foo/bar/baz';

    /**
     * @var \SprykerTest\Zed\Http\HttpCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testHandleSubRequestWithGetParams(): void
    {
        $this->tester->addRoute('test-route', static::URL_SUB_REQUEST, function (Request $request) {
            return new Response(sprintf('GET: fruit=%s', $request->query->get('fruit')));
        });

        $subRequestHandler = new SubRequestHandler($this->tester->getKernel());
        $request = new Request(static::GET_PARAMS);
        $response = $subRequestHandler->handleSubRequest($request, static::URL_SUB_REQUEST);

        $this->assertSame('GET: fruit=mango', $response->getContent());
    }

    /**
     * @return void
     */
    public function testHandleSubRequestWithPostParams(): void
    {
        $this->tester->addRoute('test-route', static::URL_SUB_REQUEST, function (Request $request) {
            return new Response(sprintf('POST: fruit=%s', $request->request->get('fruit')));
        });

        $subRequestHandler = new SubRequestHandler($this->tester->getKernel());
        $request = new Request([], static::POST_PARAMS);
        $response = $subRequestHandler->handleSubRequest($request, static::URL_SUB_REQUEST);

        $this->assertSame('POST: fruit=orange', $response->getContent());
    }
}
