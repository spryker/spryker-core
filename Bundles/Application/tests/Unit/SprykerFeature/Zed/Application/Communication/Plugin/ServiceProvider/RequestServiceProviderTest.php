<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use SprykerEngine\Zed\Kernel\AbstractUnitTest;
use SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\RequestServiceProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Application
 * @group Communication
 * @group RequestServiceProvider
 */
class RequestServiceProviderTest extends AbstractUnitTest
{

    /**
     * @dataProvider urlStack
     *
     * @param string $givenUrl
     * @param string $expectedBundle
     * @param string $expectedController
     * @param string $expectedAction
     */
    public function testBeforeMustParseRequestDataAndSetModuleControllerAndActionInRequest(
        $givenUrl,
        $expectedBundle,
        $expectedController,
        $expectedAction
    ) {
        $application = new Application();

        $requestServiceProvider = $this->getPluginByClassName('SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\RequestServiceProvider');
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
    public function urlStack()
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
