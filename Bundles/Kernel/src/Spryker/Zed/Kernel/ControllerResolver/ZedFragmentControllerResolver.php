<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\ControllerResolver;

use Silex\ControllerResolver as SilexControllerResolver;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Kernel\ClassResolver\Controller\ControllerResolver;
use Spryker\Zed\Kernel\Communication\BundleControllerAction;
use Symfony\Component\HttpFoundation\Request;

class ZedFragmentControllerResolver extends SilexControllerResolver
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

        $serviceName = 'controller.service.' . $bundleControllerAction->getBundle() . '.' . $bundleControllerAction->getController() . '.' . $bundleControllerAction->getAction();
        $serviceName .= ':' . $bundleControllerAction->getAction() . 'Action';

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
