<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Application\Communication;

use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Spryker\Shared\Kernel\Communication\RouteNameResolverInterface;
use Spryker\Shared\Kernel\Communication\ControllerLocatorInterface;
use Spryker\Shared\Kernel\LocatorLocatorInterface;

class ControllerServiceBuilder
{

    /**
     * @param \Pimple $app
     * @param LocatorLocatorInterface $locator
     * @param BundleControllerActionInterface $bundleControllerAction
     * @param ControllerLocatorInterface $controllerLocator
     * @param RouteNameResolverInterface $routeNameResolver
     *
     * @return string
     */
    public function createServiceForController(
        \Pimple $app,
        LocatorLocatorInterface $locator,
        BundleControllerActionInterface $bundleControllerAction,
        ControllerLocatorInterface $controllerLocator,
        RouteNameResolverInterface $routeNameResolver
    ) {
        $serviceName = 'controller.service.' . str_replace('/', '.', $routeNameResolver->resolve());
        $service = function () use ($app, $controllerLocator, $locator) {
            $controller = $controllerLocator->locate($app, $locator);

            return $controller;
        };

        $app[$serviceName] = $app->share($service);

        return $serviceName . ':' . $bundleControllerAction->getAction() . 'Action';
    }

}
