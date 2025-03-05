<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Sitemap\Plugin\Router;

use Spryker\Shared\Sitemap\SitemapConstants;
use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class SitemapRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    protected const ROUTE_NAME_SITEMAP_VIEW = 'sitemap-view';

    /**
     * @var string
     */
    protected const SITEMAP_FILE_NAME_REGEXP = SitemapConstants::SITEMAP_FILE_NAME_PREFIX . '[\_a-zA-Z0-9]*\\.xml';

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        return $this->addSitemapViewRoute($routeCollection);
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSitemapViewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/{sitemapFileName}',
            'Sitemap',
            'View',
        );
        $route->setRequirement('sitemapFileName', static::SITEMAP_FILE_NAME_REGEXP);
        $routeCollection->add(static::ROUTE_NAME_SITEMAP_VIEW, $route);

        return $routeCollection;
    }
}
