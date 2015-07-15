<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Application\Communication;

use SprykerEngine\Shared\Kernel\Communication\BundleControllerActionInterface;
use SprykerEngine\Shared\Kernel\Communication\RouteNameResolverInterface;
use SprykerEngine\Shared\Kernel\Communication\ControllerLocatorInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

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
