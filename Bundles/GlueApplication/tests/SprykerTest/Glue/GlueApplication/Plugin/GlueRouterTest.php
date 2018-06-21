<?php

namespace SprykerTest\Glue\GlueApplication\Plugin;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\GlueApplicationDependencyProvider;
use Spryker\Glue\GlueApplication\Plugin\Rest\GlueRouterPlugin;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use SprykerTest\Glue\GlueApplication\Stub\TestResourceRoutePlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Plugin
 * @group GlueRouterTest
 * Add your own group annotations below this line
 */
class GlueRouterTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueApplication\GlueApplicationTester
     */
    protected $tester;

    protected const ERROR_ROUTE = 'GlueApplication/Error/resource-not-found';

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->tester->setDependency(
            GlueApplicationDependencyProvider::PLUGIN_RESOURCE_ROUTES,
            [
                new TestResourceRoutePlugin(),
            ]
        );
    }

    /**
     * @return void
     */
    public function testMatchRequestWhenNoPluginRouteMatchedShouldReturnErrorRoute(): void
    {
        $glueRouterPlugin = $this->createGlueRouterPlugin();

        $request = Request::create('http://domain.tld/none/1', Request::METHOD_GET);

        $routeConfiguration = $glueRouterPlugin->matchRequest($request);

        $this->assertArrayHasKey('_controller', $routeConfiguration);
        $this->assertArrayHasKey('_route', $routeConfiguration);

        $this->assertEquals(self::ERROR_ROUTE, $routeConfiguration['_route']);
    }

    /**
     * @return void
     */
    public function testMatchRequestWhenMethodDoesNotExistShouldReturnErrorRoute(): void
    {
        $glueRouterPlugin = $this->createGlueRouterPlugin();

        $request = Request::create('http://domain.tld/none/1', Request::METHOD_GET);

        $routeConfiguration = $glueRouterPlugin->matchRequest($request);

        $this->assertArrayHasKey('_controller', $routeConfiguration);
        $this->assertArrayHasKey('_route', $routeConfiguration);

        $this->assertEquals(self::ERROR_ROUTE, $routeConfiguration['_route']);
    }

    /**
     * @return void
     */
    public function testMatchRequestWhenResourceRouteExistShouldReturnValidRoute(): void
    {
        $glueRouterPlugin = $this->createGlueRouterPlugin();

        $request = Request::create('http://domain.tld/tests/1', Request::METHOD_GET);

        $routeConfiguration = $glueRouterPlugin->matchRequest($request);

        $this->assertEquals(1, $routeConfiguration[RequestConstantsInterface::ATTRIBUTE_ID]);
        $this->assertEquals('tests', $routeConfiguration[RequestConstantsInterface::ATTRIBUTE_TYPE]);
        $this->assertArrayHasKey(RequestConstantsInterface::ATTRIBUTE_RESOURCE_FQCN, $routeConfiguration);
        $this->assertArrayHasKey(RequestConstantsInterface::ATTRIBUTE_ALL_RESOURCES, $routeConfiguration);
        $this->assertArrayHasKey('_controller', $routeConfiguration);
        $this->assertArrayHasKey('_route', $routeConfiguration);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Plugin\Rest\GlueRouterPlugin
     */
    public function createGlueRouterPlugin(): GlueRouterPlugin
    {
        return new GlueRouterPlugin();
    }
}
