<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client\HandlerStack;

use GuzzleHttp\HandlerStack;
use Spryker\Shared\ZedRequest\Client\Middleware\MiddlewareInterface;

class HandlerStackContainer
{

    /**
     * @var \GuzzleHttp\HandlerStack
     */
    protected static $handlerStack;

    /**
     * @return \GuzzleHttp\HandlerStack
     */
    public function getHandlerStack()
    {
        if (!static::$handlerStack) {
            static::$handlerStack = HandlerStack::create();
        }

        return static::$handlerStack;
    }

    /**
     * @param \Spryker\Shared\ZedRequest\Client\Middleware\MiddlewareInterface $middleware
     *
     * @return void
     */
    public function addMiddleware(MiddlewareInterface $middleware)
    {
        $handlerStack = $this->getHandlerStack();
        $handlerStack->push($middleware->getCallable(), $middleware->getName());
    }

}
