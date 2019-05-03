<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\ControllerResolver;

use Silex\ControllerResolver as SilexControllerResolver;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;
use Spryker\Yves\Kernel\BundleControllerAction;
use Spryker\Yves\Kernel\ClassResolver\Controller\ControllerResolver;

class YvesFragmentControllerResolver extends SilexControllerResolver
{
    /**
     * @param string $controller
     *
     * @return array
     */
    protected function createController($controller)
    {
        [$moduleName, $controllerName, $actionName] = explode(':', $controller);
        $bundleControllerAction = new BundleControllerAction($moduleName, $controllerName, $actionName);
        $controller = $this->resolveController($bundleControllerAction);

        return [$controller, $bundleControllerAction->getAction() . 'Action'];
    }

    /**
     * @param \Spryker\Shared\Kernel\Communication\BundleControllerActionInterface $bundleControllerAction
     *
     * @return \Spryker\Yves\Kernel\Controller\AbstractController
     */
    protected function resolveController(BundleControllerActionInterface $bundleControllerAction)
    {
        $controllerResolver = new ControllerResolver();

        /** @var \Spryker\Yves\Kernel\Controller\AbstractController $controller */
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
