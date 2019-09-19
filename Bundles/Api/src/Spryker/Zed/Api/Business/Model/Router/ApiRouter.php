<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Router;

use Silex\Application;
use Spryker\Shared\Application\Communication\ControllerServiceBuilder;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Kernel\ClassResolver\Controller\ControllerResolver;
use Spryker\Zed\Kernel\Communication\BundleControllerAction;
use Spryker\Zed\Kernel\Communication\Controller\RouteNameResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class ApiRouter implements RouterInterface
{
    /**
     * @var \Symfony\Component\Routing\RequestContext
     */
    private $context;

    /**
     * @var \Silex\Application
     */
    private $app;

    /**
     * @param \Silex\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param \Symfony\Component\Routing\RequestContext $context
     *
     * @return void
     */
    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    /**
     * @return \Symfony\Component\Routing\RequestContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Gets the RouteCollection instance associated with this Router.
     *
     * @return \Symfony\Component\Routing\RouteCollection A RouteCollection instance
     */
    public function getRouteCollection()
    {
        return new RouteCollection();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     *
     * @return void
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        throw new RouteNotFoundException();
    }

    /**
     * @inheritdoc
     */
    public function match($pathinfo)
    {
        /** @var \Symfony\Component\HttpFoundation\Request $request */
        $request = $this->app['request_stack']->getCurrentRequest();

        $path = $request->getPathInfo();
        $this->assertValidPath($path);

        $controllerResolver = new ControllerResolver();
        $routeNameResolver = new RouteNameResolver($request);
        $bundleControllerAction = new BundleControllerAction(
            $request->attributes->get('module'),
            $request->attributes->get('controller'),
            'index'
        );

        $service = (new ControllerServiceBuilder())->createServiceForController(
            $this->app,
            $bundleControllerAction,
            $controllerResolver,
            $routeNameResolver
        );

        return [
            '_controller' => $service,
            '_route' => 'Api/Rest/index',
        ];
    }

    /**
     * @param string $path
     *
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     *
     * @return void
     */
    protected function assertValidPath($path)
    {
        if (strpos($path, ApiConfig::ROUTE_PREFIX_API_REST) !== 0) {
            throw new ResourceNotFoundException(sprintf(
                'Invalid URI prefix, expected %s in path %s',
                ApiConfig::ROUTE_PREFIX_API_REST,
                $path
            ));
        }
    }
}
