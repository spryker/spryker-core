<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Route;

interface RouteInterface
{
    /**
     * @param string $variable The variable name
     * @param string $regexp The regexp to apply
     *
     * @return $this
     */
    public function assert($variable, $regexp);

    /**
     * @param string $variable The variable name
     * @param mixed $callback A PHP callback that converts the original value
     *
     * @return $this
     */
    public function convert($variable, $callback);

    /**
     * @return $this
     */
    public function requireHttp();

    /**
     * @return $this
     */
    public function requireHttps();

    /**
     * @param \Closure $callback
     *
     * @return $this
     */
    public function before($callback);

    /**
     * @param \Closure $callback
     *
     * @return $this
     */
    public function after($callback);
}
