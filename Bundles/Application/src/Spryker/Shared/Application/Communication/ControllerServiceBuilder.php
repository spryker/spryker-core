<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Application\Communication;

use Spryker\Shared\Kernel\ClassResolver\Controller\AbstractControllerResolver;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Spryker\Shared\Kernel\Communication\RouteNameResolverInterface;

class ControllerServiceBuilder
{

    /**
     * @param \Pimple $application
     * @param BundleControllerActionInterface $bundleControllerAction
     * @param AbstractControllerResolver $controllerResolver
     * @param RouteNameResolverInterface $routeNameResolver
     *
     * @return string
     */
    public function createServiceForController(
        \Pimple $application,
        BundleControllerActionInterface $bundleControllerAction,
        AbstractControllerResolver $controllerResolver,
        RouteNameResolverInterface $routeNameResolver
    ) {
        $serviceName = 'controller.service.' . str_replace('/', '.', $routeNameResolver->resolve());
        $service = function () use ($application, $controllerResolver, $bundleControllerAction) {
            $controller = $controllerResolver->resolve($bundleControllerAction);
            $controller->setApplication($application);

            return $controller;
        };

        $application[$serviceName] = $application->share($service);

        return $serviceName . ':' . $bundleControllerAction->getAction() . 'Action';
    }

}
