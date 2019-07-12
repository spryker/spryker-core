<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Resolver;

use InvalidArgumentException;
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
     *
     * @throws \InvalidArgumentException
     */
    public function getController(Request $request)
    {
        $controller = $request->attributes->get('_controller');
        if (!$controller) {
            return false;
        }

        if (is_string($controller)) {
            [$controllerServiceIdentifier, $actionName] = explode(':', $controller);
            if ($this->container->has($controllerServiceIdentifier)) {
                return [$this->container->get($controllerServiceIdentifier), $actionName];
            }
        }

        if (is_array($controller)) {
            if (is_callable($controller[0])) {
                $controllerInstance = $controller[0]();
                $controllerInstance = $this->injectContainerAndInitialize($controllerInstance);

                return [$controllerInstance, $controller[1]];
            }

            $controllerInstance = new $controller[0]();
            $controllerInstance = $this->injectContainerAndInitialize($controllerInstance);

            return [$controllerInstance, $controller[1]];
        }

        if (is_object($controller)) {
            if (method_exists($controller, '__invoke')) {
                return $controller;
            }

            throw new InvalidArgumentException(sprintf('Controller "%s" for URI "%s" is not callable.', get_class($controller), $request->getPathInfo()));
        }

        return false;
    }

    /**
     * @param mixed $controller
     *
     * @return mixed
     */
    protected function injectContainerAndInitialize($controller)
    {
        if (method_exists($controller, 'setApplication')) {
            $controller->setApplication($this->container);
        }

        if (method_exists($controller, 'initialize')) {
            $controller->initialize();
        }

        return $controller;
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
