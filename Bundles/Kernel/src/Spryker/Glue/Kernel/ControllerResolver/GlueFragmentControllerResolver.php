<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\ControllerResolver;

use Silex\ControllerResolver as SilexControllerResolver;
use Spryker\Glue\Kernel\BundleControllerAction;
use Spryker\Glue\Kernel\ClassResolver\Controller\ControllerResolver;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;

class GlueFragmentControllerResolver extends SilexControllerResolver
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
     * @param \Spryker\Shared\Kernel\Communication\BundleControllerActionInterface $bundleControllerAction
     *
     * @return \Spryker\Glue\Kernel\Controller\AbstractController
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
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getCurrentRequest()
    {
        return $this->app['request_stack']->getCurrentRequest();
    }
}
