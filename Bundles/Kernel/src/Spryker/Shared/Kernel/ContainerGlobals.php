<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

/*
 * This class is a modified copy of \Pimple @see https://github.com/silexphp/Pimple/blob/v1.1.1
 *
 * This file is part of Pimple.
 *
 * Copyright (c) 2009 Fabien Potencier
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Spryker\Shared\Kernel;

use ArrayAccess;
use InvalidArgumentException;

class ContainerGlobals implements ArrayAccess
{
    /**
     * @var array
     */
    protected static $containerGlobals = [];

    /**
     * @return array
     */
    public function getContainerGlobals()
    {
        return self::$containerGlobals;
    }

    /**
     * @param string $id
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($id, $value)
    {
        self::$containerGlobals[$id] = $value;
    }

    /**
     * @param string $id
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function offsetGet($id)
    {
        if (!array_key_exists($id, self::$containerGlobals)) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }

        $isFactory = is_object(self::$containerGlobals[$id]) && method_exists(self::$containerGlobals[$id], '__invoke');

        return $isFactory ? self::$containerGlobals[$id]($this) : self::$containerGlobals[$id];
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function offsetExists($id)
    {
        return array_key_exists($id, self::$containerGlobals);
    }

    /**
     * @param string $id
     *
     * @return void
     */
    public function offsetUnset($id)
    {
        unset(self::$containerGlobals[$id]);
    }

    /**
     * @param callable $callable
     *
     * @throws \InvalidArgumentException
     *
     * @return \Closure
     */
    public static function share($callable)
    {
        if (!is_object($callable) || !method_exists($callable, '__invoke')) {
            throw new InvalidArgumentException('Service definition is not a Closure or invokable object.');
        }

        return function ($c) use ($callable) {
            static $object;

            if ($object === null) {
                $object = $callable($c);
            }

            return $object;
        };
    }

    /**
     * @param callable $callable
     *
     * @throws \InvalidArgumentException
     *
     * @return \Closure
     */
    public static function protect($callable)
    {
        if (!is_object($callable) || !method_exists($callable, '__invoke')) {
            throw new InvalidArgumentException('Callable is not a Closure or invokable object.');
        }

        return function ($c) use ($callable) {
            return $callable;
        };
    }

    /**
     * @param string $id
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function raw($id)
    {
        if (!array_key_exists($id, self::$containerGlobals)) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }

        return self::$containerGlobals[$id];
    }

    /**
     * @param string $id
     * @param callable $callable
     *
     * @throws \InvalidArgumentException
     *
     * @return \Closure
     */
    public function extend($id, $callable)
    {
        if (!array_key_exists($id, self::$containerGlobals)) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }

        if (!is_object(self::$containerGlobals[$id]) || !method_exists(self::$containerGlobals[$id], '__invoke')) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" does not contain an object definition.', $id));
        }

        if (!is_object($callable) || !method_exists($callable, '__invoke')) {
            throw new InvalidArgumentException('Extension service definition is not a Closure or invokable object.');
        }

        $factory = self::$containerGlobals[$id];

        return self::$containerGlobals[$id] = function ($c) use ($callable, $factory) {
            return $callable($factory($c), $c);
        };
    }

    /**
     * @return array
     */
    public function keys()
    {
        return array_keys(self::$containerGlobals);
    }
}
