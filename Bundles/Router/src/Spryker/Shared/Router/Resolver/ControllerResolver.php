<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Router\Resolver;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

class ControllerResolver implements ControllerResolverInterface
{
    /**
     * @var \Spryker\Service\Container\ContainerInterface
     */
    protected $container;

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     *
     * This method looks for a '_controller' request attribute that represents
     * the controller.
     */
    public function getController(Request $request)
    {
        $controller = $request->attributes->get('_controller');
        if (!$controller) {
            return false;
        }

        // When we have a string for the controller we assume that the controller can be found in the Container.
        if (is_string($controller)) {
            [$controllerServiceIdentifier, $actionName] = explode(':', $controller);
            if ($this->container->has($controllerServiceIdentifier)) {
                return [$this->container->get($controllerServiceIdentifier), $actionName];
            }
        }

        if (!is_array($controller)) {
            return false;
        }

        $instantiatedController = new $controller[0]();
        if (method_exists($instantiatedController, 'setApplication')) {
            $instantiatedController->setApplication($this->container);
        }

        if (method_exists($instantiatedController, 'initialize')) {
            $instantiatedController->initialize();
        }

        return [$instantiatedController, $controller[1]];
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated This method is deprecated as of 3.1 and will be removed in 4.0. Implement the ArgumentResolverInterface and inject it in the HttpKernel instead.
     */
    public function getArguments(Request $request, $controller)
    {
    }
}
