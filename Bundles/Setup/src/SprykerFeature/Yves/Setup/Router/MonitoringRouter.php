<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Setup\Router;

use SprykerFeature\Shared\Application\Communication\ControllerServiceBuilder;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Yves\Application\Business\Routing\AbstractRouter;
use SprykerEngine\Yves\Kernel\Communication\BundleControllerAction;
use SprykerEngine\Yves\Kernel\Communication\Controller\BundleControllerActionRouteNameResolver;
use SprykerEngine\Yves\Kernel\Communication\ControllerLocator;
use Silex\Application;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * We need this router because other routers may already use memcache/solr
 */
class MonitoringRouter extends AbstractRouter
{

    const HEARTBEAT_URL = 'system/heartbeat';

    /**
     * @var LocatorLocatorInterface
     */
    protected $locator;

    /**
     * @param LocatorLocatorInterface $locator
     * @param Application $app
     * @param bool $sslEnabled
     */
    public function __construct(LocatorLocatorInterface $locator, Application $app, $sslEnabled = false)
    {
        $this->locator = $locator;
        parent::__construct($app, $sslEnabled);
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
        if (false !== strpos($pathinfo, self::HEARTBEAT_URL)) {
            $bundleControllerAction = new BundleControllerAction('System', 'Heartbeat', 'index');
            $controllerResolver = new ControllerLocator($bundleControllerAction);

            $routeResolver = new BundleControllerActionRouteNameResolver($bundleControllerAction);

            $service = (new ControllerServiceBuilder())->createServiceForController(
                $this->app,
                $this->locator,
                $bundleControllerAction,
                $controllerResolver,
                $routeResolver
            );

            return [
                '_controller' => $service,
                '_route' => $routeResolver->resolve(),
            ];
        }

        throw new ResourceNotFoundException();
    }

}
