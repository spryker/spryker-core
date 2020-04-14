<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Application\Communication\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Silex\Application;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\RequestServiceProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Application
 * @group Communication
 * @group Plugin
 * @group ServiceProvider
 * @group RequestServiceProviderTest
 * Add your own group annotations below this line
 */
class RequestServiceProviderTest extends Unit
{
    /**
     * @dataProvider urlStack
     *
     * @param string $givenUrl
     * @param string $expectedBundle
     * @param string $expectedController
     * @param string $expectedAction
     *
     * @return void
     */
    public function testBeforeMustParseRequestDataAndSetBundleControllerAndActionInRequest(
        string $givenUrl,
        string $expectedBundle,
        string $expectedController,
        string $expectedAction
    ): void {
        $application = new Application();

        $requestServiceProvider = new RequestServiceProvider();
        $requestServiceProvider->boot($application);

        $request = Request::create($givenUrl);
        $application->handle($request);

        $this->assertSame($expectedBundle, $request->attributes->get(RequestServiceProvider::BUNDLE));
        $this->assertSame($expectedController, $request->attributes->get(RequestServiceProvider::CONTROLLER));
        $this->assertSame($expectedAction, $request->attributes->get(RequestServiceProvider::ACTION));
    }

    /**
     * @return array
     */
    public function urlStack(): array
    {
        return [
            ['/foo', 'foo', 'index', 'index'],
            ['/foo/bar', 'foo', 'bar', 'index'],
            ['/foo/bar/baz', 'foo', 'bar', 'baz'],
            ['/foo/bar/baz?foo=bar', 'foo', 'bar', 'baz'],
            ['/foo/bar/baz?foo=bar&bar=baz', 'foo', 'bar', 'baz'],
        ];
    }
}
