<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business\Model\Router;

use Spryker\Shared\Application\Communication\ControllerServiceBuilder;
use Spryker\Zed\Kernel\ClassResolver\Controller\ControllerResolver;
use Spryker\Zed\Kernel\Communication\BundleControllerAction;
use Spryker\Zed\Kernel\Communication\Controller\RouteNameResolver;
use Silex\Application;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class MvcRouter implements RouterInterface
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
     * @return RouteCollection A RouteCollection instance
     */
    public function getRouteCollection()
    {
        return new RouteCollection();
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        throw new RouteNotFoundException();
    }

    /**
     * {@inheritdoc}
     */
    public function match($pathinfo)
    {
        $request = $this->app['request_stack']->getCurrentRequest();
        $bundleControllerAction = new BundleControllerAction($request);
        $controllerResolver = new ControllerResolver();

        if (!$controllerResolver->isResolveAble($bundleControllerAction)) {
            throw new ResourceNotFoundException();
        }

        $routeNameResolver = new RouteNameResolver($request);

        $service = (new ControllerServiceBuilder())->createServiceForController(
            $this->app,
            $bundleControllerAction,
            $controllerResolver,
            $routeNameResolver
        );

        return [
            '_controller' => $service,
            '_route' => $routeNameResolver->resolve(),
        ];
    }

}
