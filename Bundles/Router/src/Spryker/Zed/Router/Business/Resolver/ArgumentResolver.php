<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Resolver;

use Closure;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionObject;
use ReflectionParameter;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;

class ArgumentResolver implements ArgumentResolverInterface
{
    /**
     * @var bool
     */
    protected $supportsVariadic;

    /**
     * @var bool
     */
    protected $supportsScalarTypes;

    public function __construct()
    {
        $this->supportsVariadic = method_exists(ReflectionParameter::class, 'isVariadic');
        $this->supportsScalarTypes = method_exists(ReflectionParameter::class, 'getType');
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments(Request $request, $controller)
    {
        if (is_array($controller)) {
            $r = new ReflectionMethod($controller[0], $controller[1]);
        } elseif (is_object($controller) && !$controller instanceof Closure) {
            $r = new ReflectionObject($controller);
            $r = $r->getMethod('__invoke');
        } else {
            $r = new ReflectionFunction($controller);
        }

        return $this->doGetArguments($request, $controller, $r->getParameters());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param callable $controller
     * @param \ReflectionParameter[] $parameters
     *
     * @throws \RuntimeException
     *
     * @return array The arguments to use when calling the action
     */
    protected function doGetArguments(Request $request, $controller, array $parameters)
    {
        $attributes = $request->attributes->all();
        $arguments = [];
        foreach ($parameters as $param) {
            if (array_key_exists($param->name, $attributes)) {
                if ($this->supportsVariadic && $param->isVariadic() && is_array($attributes[$param->name])) {
                    $arguments = array_merge($arguments, array_values($attributes[$param->name]));
                } else {
                    $arguments[] = $attributes[$param->name];
                }
            } elseif ($param->getClass() && $param->getClass()->isInstance($request)) {
                $arguments[] = $request;
            } elseif ($param->isDefaultValueAvailable()) {
                $arguments[] = $param->getDefaultValue();
            } elseif ($this->supportsScalarTypes && $param->hasType() && $param->allowsNull()) {
                $arguments[] = null;
            } else {
                if (is_array($controller)) {
                    $repr = sprintf('%s::%s()', get_class($controller[0]), $controller[1]);
                } elseif (is_object($controller)) {
                    $repr = get_class($controller);
                } else {
                    $repr = $controller;
                }

                throw new RuntimeException(sprintf('Controller "%s" requires that you provide a value for the "$%s" argument (because there is no default value or because there is a non optional argument after this one).', $repr, $param->name));
            }
        }

        return $arguments;
    }
}
