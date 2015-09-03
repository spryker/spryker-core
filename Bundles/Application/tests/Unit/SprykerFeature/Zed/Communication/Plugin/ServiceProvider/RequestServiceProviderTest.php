<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Communication\Plugin\ServiceProvider;

use Silex\Application;
use SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider\RequestServiceProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Communication
 * @group RequestServiceProvider
 */
class RequestServiceProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider urlStack
     *
     * @param $url
     * @param $module
     * @param $controller
     * @param $action
     */
    public function testBeforeMustParseRequestDataAndSetModuleControllerAndActionInRequest($url, $module, $controller, $action)
    {
        $application = new Application();

        $requestServiceProvider = new RequestServiceProvider();
        $requestServiceProvider->boot($application);

        $request = Request::create($url);
        $application->handle($request);

        $this->assertSame($module, $request->attributes->get('module'));
        $this->assertSame($controller, $request->attributes->get('controller'));
        $this->assertSame($action, $request->attributes->get('action'));
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
