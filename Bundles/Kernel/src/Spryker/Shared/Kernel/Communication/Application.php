<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Communication;

use Silex\Application as SilexApplication;
use Silex\Application\TranslationTrait;
use Silex\Application\TwigTrait;
use Silex\Application\UrlGeneratorTrait;
use Symfony\Cmf\Component\Routing\ChainRouter;
use Symfony\Component\Routing\RouterInterface;

class Application extends SilexApplication
{
    use TranslationTrait;
    use TwigTrait;
    use UrlGeneratorTrait;

    public const REQUEST = 'request';

    /**
     * @deprecated Use `\Spryker\Shared\Application\Application::SERVICE_ROUTER` instead.
     */
    public const ROUTERS = 'routers';
    public const REQUEST_STACK = 'request_stack';

    /**
     * @deprecated Use
     *
     * @param \Symfony\Component\Routing\RouterInterface $router The router
     * @param int $priority The priority of the router
     *
     * @return void
     */
    public function addRouter(RouterInterface $router, $priority = 0)
    {
        /** @var \Pimple $this */
        $this[static::ROUTERS] = $this->share($this->extend(static::ROUTERS, function (ChainRouter $chainRouter) use ($router, $priority) {
            $chainRouter->add($router, $priority);

            return $chainRouter;
        }));
    }
}
