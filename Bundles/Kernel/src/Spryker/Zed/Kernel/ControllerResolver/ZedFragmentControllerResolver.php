<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ControllerResolver;

use Silex\ControllerResolver as SilexControllerResolver;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Spryker\Zed\Kernel\ClassResolver\Controller\ControllerResolver;
use Spryker\Zed\Kernel\Communication\BundleControllerAction;

class ZedFragmentControllerResolver extends SilexControllerResolver
{
    /**
     * @param string $controller
     *
     * @return array
     */
    protected function createController($controller)
    {
        if (strpos($controller, ':') !== false) {
            [$moduleName, $controllerName, $actionName] = explode(':', $controller);

            $bundleControllerAction = new BundleControllerAction($moduleName, $controllerName, $actionName);
            $controller = $this->resolveController($bundleControllerAction);

            return [$controller, $bundleControllerAction->getAction() . 'Action'];
        }

        [$bundle, $controllerName, $actionName] = explode('/', ltrim($controller, '/'));

        $bundleControllerAction = new BundleControllerAction($bundle, $controllerName, $actionName);
        $controller = $this->resolveController($bundleControllerAction);

        $serviceName = 'controller.service.' . $bundleControllerAction->getBundle() . '.' . $bundleControllerAction->getController() . '.' . $bundleControllerAction->getAction();
        $serviceName .= ':' . $bundleControllerAction->getAction() . 'Action';

        $request = $this->getCurrentRequest();
        $request->attributes->set('_controller', $serviceName);

        return [$controller, $bundleControllerAction->getAction() . 'Action'];
    }

    /**
     * @param \Spryker\Shared\Kernel\Communication\BundleControllerActionInterface $bundleControllerAction
     *
     * @return \Spryker\Zed\Kernel\Communication\Controller\AbstractController
     */
    protected function resolveController(BundleControllerActionInterface $bundleControllerAction)
    {
        $controllerResolver = new ControllerResolver();

        /** @var \Spryker\Zed\Kernel\Communication\Controller\AbstractController $controller */
        $controller = $controllerResolver->resolve($bundleControllerAction);
        $controller->setApplication($this->app);
        $controller->initialize();

        return $controller;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getCurrentRequest()
    {
        return $this->app['request_stack']->getCurrentRequest();
    }
}
