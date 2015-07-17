<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Router;

use SprykerFeature\Shared\Application\Communication\ControllerServiceBuilder;
use SprykerEngine\Zed\Kernel\Communication\BundleControllerAction;
use SprykerEngine\Zed\Kernel\Communication\Controller\RouteNameResolver;
use SprykerEngine\Zed\Kernel\Communication\ControllerLocator;
use SprykerEngine\Zed\Kernel\Locator;
use Silex\Application;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class MvcRouter implements RouterInterface
{

    /**
     * @var RequestContext
     */
    private $context;

    /**
     * @var Application
     */
    private $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param RequestContext $context
     */
    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    /**
     * @return RequestContext
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
        $controllerLocator = new ControllerLocator($bundleControllerAction);

        if (!$controllerLocator->canLocate()) {
            throw new ResourceNotFoundException();
        }

        $routeNameResolver = new RouteNameResolver($request);

        $service = (new ControllerServiceBuilder())->createServiceForController(
            $this->app,
            Locator::getInstance(),
            $bundleControllerAction,
            $controllerLocator,
            $routeNameResolver
        );

        return [
            '_controller' => $service,
            '_route' => $routeNameResolver->resolve(),
        ];
    }

}
