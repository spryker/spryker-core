<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Communication\Resolver;

use Spryker\Shared\Router\Resolver\ControllerResolver as SharedControllerResolver;
use Spryker\Zed\Kernel\ClassResolver\Controller\ControllerResolver as ControllerClassResolver;
use Spryker\Zed\Kernel\Communication\BundleControllerAction;
use Symfony\Component\HttpFoundation\Request;

class ControllerResolver extends SharedControllerResolver
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $controller
     *
     * @return callable|bool
     */
    protected function getControllerFromString(Request $request, string $controller)
    {
        if (strpos($controller, '/') !== false) {
            return $this->resolveControllerFromUri($controller, $request);
        }

        return parent::getControllerFromString($request, $controller);
    }

    /**
     * @param string $uri
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return callable
     */
    private function resolveControllerFromUri(string $uri, Request $request)
    {
        [$module, $controllerName, $actionName] = explode('/', ltrim($uri, '/'));

        $bundleControllerAction = new BundleControllerAction($module, $controllerName, $actionName);
        $controllerResolver = new ControllerClassResolver();

        $controller = $controllerResolver->resolve($bundleControllerAction);
        $controller = $this->injectContainerAndInitialize($controller);

        $request->attributes->set(
            '_template',
            sprintf(
                '%s/%s/%s',
                $bundleControllerAction->getBundle(),
                $bundleControllerAction->getController(),
                $bundleControllerAction->getAction(),
            ),
        );

        /** @var callable $callable */
        $callable = [$controller, $bundleControllerAction->getAction() . 'Action'];

        return $callable;
    }
}
