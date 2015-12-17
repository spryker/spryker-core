<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Application\Communication;

use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Spryker\Shared\Kernel\Communication\RouteNameResolverInterface;
use Spryker\Shared\Kernel\Communication\ControllerLocatorInterface;

class ControllerServiceBuilder
{

    /**
     * @param \Pimple $application
     * @param BundleControllerActionInterface $bundleControllerAction
     * @param ControllerLocatorInterface $controllerLocator
     * @param RouteNameResolverInterface $routeNameResolver
     *
     * @return string
     */
    public function createServiceForController(
        \Pimple $application,
        BundleControllerActionInterface $bundleControllerAction,
        ControllerLocatorInterface $controllerLocator,
        RouteNameResolverInterface $routeNameResolver
    ) {
        $serviceName = 'controller.service.' . str_replace('/', '.', $routeNameResolver->resolve());
        $service = function () use ($application, $controllerLocator) {
            $controller = $controllerLocator->locate();
            $controller->setApplication($application);

            return $controller;
        };

        $application[$serviceName] = $application->share($service);

        return $serviceName . ':' . $bundleControllerAction->getAction() . 'Action';
    }

}
