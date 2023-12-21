<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Router;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Security\Exception\FirewallNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

class SecurityRouter implements SecurityRouterInterface
{
    /**
     * @uses \Spryker\Zed\Router\Communication\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     *
     * @var string
     */
    protected const SERVICE_ROUTER = 'routers';

    /**
     * @var string
     */
    protected const CONTROLLER = '_controller';

    /**
     * @var string
     */
    protected const FIREWALL_NOT_FOUND_MESSAGE = 'None of the configured firewalls matched. Please check your firewall configuration.';

    /**
     * @var array<array<string>>
     */
    protected array $securityRoutes = [];

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return void
     */
    public function addRouter(ContainerInterface $container): void
    {
        $loader = new ClosureLoader();
        $securityRoutes = $this->securityRoutes;

        $resource = function () use ($securityRoutes): RouteCollection {
            $routeCollection = new RouteCollection();
            foreach ($securityRoutes as $route) {
                [$url, $name] = $route;

                $route = new Route($url);

                $controller = function (Request $request): void {
                    throw new FirewallNotFoundException(static::FIREWALL_NOT_FOUND_MESSAGE);
                };

                $route->setDefault(static::CONTROLLER, $controller);

                $routeCollection->add($name, $route, 0);
            }

            return $routeCollection;
        };

        $router = new Router($loader, $resource, []);
        $container->get(static::SERVICE_ROUTER)->add($router, 1);
    }

    /**
     * @param string $routeNameOrUrl
     * @param string|null $routeName
     *
     * @return void
     */
    public function addSecurityRoute(
        string $routeNameOrUrl,
        ?string $routeName = null
    ): void {
        $url = $this->buildUrl($routeNameOrUrl);
        $routeName = $this->buildRouteName($routeNameOrUrl, $routeName);

        $this->securityRoutes[] = [$url, $routeName];
    }

    /**
     * @param string $routeNameOrUrl
     *
     * @return string
     */
    protected function buildUrl(string $routeNameOrUrl): string
    {
        if ($routeNameOrUrl[0] === '/') {
            return $routeNameOrUrl;
        }

        return '/' . str_replace('_', '/', ltrim($routeNameOrUrl, '/'));
    }

    /**
     * @param string $routeNameOrUrl
     * @param string|null $routeName
     *
     * @return string
     */
    protected function buildRouteName(string $routeNameOrUrl, ?string $routeName = null): string
    {
        if ($routeName) {
            return $routeName;
        }

        return str_replace('/', '_', ltrim($routeNameOrUrl, '/'));
    }
}
