<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Router\RouterResource;

use ReflectionMethod;
use SplFileInfo;
use Spryker\Zed\Router\Business\Route\Route;
use Spryker\Zed\Router\Business\Route\RouteCollection;
use Symfony\Component\Finder\Finder;

class MerchantPortalRouterResource extends AbstractRouterResource
{
    /**
     * @var string
     */
    protected const MERCHANT_PORTAL_FOLDER_FILTER = 'MerchantPortal';

    /**
     * @var string
     */
    protected const APPLICATION_MODULE_NAME = 'merchant-portal-application';

    /**
     * @var string
     */
    protected const ROUTE_NAME_SECURITY_MERCHANT_PORTAL_GUI_LOGIN = 'security-merchant-portal-gui:login';

    /**
     * @var string
     */
    protected const ROUTE_NAME_SECURITY_GUI_LOGIN = 'security-gui:login';

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getFinder(): Finder
    {
        $finder = new Finder();
        $finder->files()
            ->in($this->config->getControllerDirectories())
            ->name('*Controller.php')
            ->notName('GatewayController.php')
            ->filter(function (SplFileInfo $item) {
                return strpos($item->getPathname(), static::MERCHANT_PORTAL_FOLDER_FILTER) !== false || strpos($item->getFilename(), static::MERCHANT_PORTAL_FOLDER_FILTER) !== false;
            });

        return $finder;
    }

    /**
     * @param \ReflectionMethod $method
     * @param \Spryker\Zed\Router\Business\Route\RouteCollection $routeCollection
     * @param string $pathCandidate
     * @param string $controllerClassName
     * @param string $template
     *
     * @return \Spryker\Zed\Router\Business\Route\RouteCollection
     */
    protected function addRouteToCollection(
        ReflectionMethod $method,
        RouteCollection $routeCollection,
        string $pathCandidate,
        string $controllerClassName,
        string $template
    ): RouteCollection {
        $route = new Route($pathCandidate);

        $route->addDefaults([
            '_controller' => [$controllerClassName, $method->getName()],
            '_template' => $template,
        ]);

        $routeName = str_replace('/', ':', trim($pathCandidate, '/'));

        $routeCollection->add($routeName, $route, 0);

        if ($routeName === static::ROUTE_NAME_SECURITY_MERCHANT_PORTAL_GUI_LOGIN) {
            $routeCollection->add(static::ROUTE_NAME_SECURITY_GUI_LOGIN, $route, 0);
        }

        return $routeCollection;
    }
}
