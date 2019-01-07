<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Router\Route;

use Symfony\Component\Routing\Route as SymfonyRoute;

class Route extends SymfonyRoute implements RouteInterface
{
    /**
     * @param string $variable
     * @param string $regexp
     *
     * @return $this
     */
    public function assert($variable, $regexp)
    {
        $this->setRequirement($variable, $regexp);

        return $this;
    }

    /**
     * @param string $variable
     * @param mixed $default
     *
     * @return $this
     */
    public function value($variable, $default)
    {
        $this->setDefault($variable, $default);

        return $this;
    }

    /**
     * @param string $variable
     * @param mixed $callback
     *
     * @return $this
     */
    public function convert($variable, $callback)
    {
        $converters = $this->getOption('_converters');
        $converters[$variable] = $callback;
        $this->setOption('_converters', $converters);

        return $this;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function method($method)
    {
        $this->setMethods(explode('|', $method));

        return $this;
    }

    /**
     * @param string $host
     *
     * @return $this
     */
    public function host($host)
    {
        $this->setHost($host);

        return $this;
    }

    /**
     * @return $this
     */
    public function requireHttp()
    {
        $this->setSchemes('http');

        return $this;
    }

    /**
     * @return $this
     */
    public function requireHttps()
    {
        $this->setSchemes('https');

        return $this;
    }

    /**
     * @param \Closure $callback
     *
     * @return $this
     */
    public function before($callback)
    {
        $callbacks = $this->getOption('_before_middlewares');
        $callbacks[] = $callback;
        $this->setOption('_before_middlewares', $callbacks);

        return $this;
    }

    /**
     * @param \Closure $callback
     *
     * @return $this
     */
    public function after($callback)
    {
        $callbacks = $this->getOption('_after_middlewares');
        $callbacks[] = $callback;
        $this->setOption('_after_middlewares', $callbacks);

        return $this;
    }
}
