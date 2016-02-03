<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel\ControllerResolver;

use Silex\ControllerResolver as SilexControllerResolver;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Spryker\Yves\Application\Controller\AbstractController;
use Spryker\Yves\Kernel\BundleControllerAction;
use Spryker\Yves\Kernel\ClassResolver\Controller\ControllerResolver;
use Symfony\Component\HttpFoundation\Request;

class YvesFragmentControllerResolver extends SilexControllerResolver
{

    /**
     * @param string $controller
     *
     * @return array
     */
    protected function createController($controller)
    {
        list($bundle, $controllerName, $actionName) = explode('/', ltrim($controller, '/'));

        $bundleControllerAction = new BundleControllerAction($bundle, $controllerName, $actionName);
        $controller = $this->resolveController($bundleControllerAction);

        $serviceName = get_class($controller) . '::' . $bundleControllerAction->getAction() . 'Action';

        $request = $this->getCurrentRequest();
        $request->attributes->set('_controller', $serviceName);

        return [$controller, $bundleControllerAction->getAction() . 'Action'];
    }

    /**
     * @param BundleControllerActionInterface $bundleControllerAction
     *
     * @throws \Spryker\Shared\Kernel\ClassResolver\Controller\ControllerNotFoundException
     *
     * @return AbstractController
     */
    protected function resolveController(BundleControllerActionInterface $bundleControllerAction)
    {
        $controllerResolver = new ControllerResolver();

        $controller = $controllerResolver->resolve($bundleControllerAction);
        $controller->setApplication($this->app);
        $controller->initialize();

        return $controller;
    }

    /**
     * @return Request
     */
    protected function getCurrentRequest()
    {
        return $this->app['request_stack']->getCurrentRequest();
    }

}
