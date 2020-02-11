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

/**
 * Deprecated: Please use `\Spryker\Shared\Application\Application` instead.
 */
class Application extends SilexApplication
{
    use TranslationTrait;
    use TwigTrait;
    use UrlGeneratorTrait;

    /**
     * @see \Spryker\Shared\Application\Application::SERVICE_ROUTER
     */
    public const ROUTERS = 'routers';

    /**
     * @see \Spryker\Shared\Application\Application::SERVICE_REQUEST
     */
    public const REQUEST = 'request';

    /**
     * @dsee \Spryker\Shared\Application\Application::SERVICE_REQUEST_STACK
     */
    public const REQUEST_STACK = 'request_stack';

    /**
     * @deprecated Use `\Spryker\Zed\Router\RouterDependencyProvider::getRouterPlugins()` instead.
     * @deprecated Use `\Spryker\Yves\Router\RouterDependencyProvider::getRouterPlugins()` instead.
     *
     * @param \Symfony\Component\Routing\RouterInterface $router The router
     * @param int $priority The priority of the router
     *
     * @return void
     */
    public function addRouter(RouterInterface $router, $priority = 0)
    {
        /** @var \Spryker\Service\Container\ContainerInterface $this */
        $this->set(static::ROUTERS, $this->extend(static::ROUTERS, function (ChainRouter $chainRouter) use ($router, $priority) {
            $chainRouter->add($router, $priority);

            return $chainRouter;
        }));
    }
}
