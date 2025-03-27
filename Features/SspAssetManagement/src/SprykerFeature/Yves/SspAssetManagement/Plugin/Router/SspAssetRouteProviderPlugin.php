<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspAssetManagement\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class SspAssetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_ASSET_DETAILS = 'customer/asset/details';

    /**
     * @var string
     */
    public const ROUTE_NAME_ASSET_CREATE = 'customer/asset/create';

    /**
     * @var string
     */
    public const ROUTE_NAME_ASSET_UPDATE = 'customer/asset/update';

    /**
     * @var string
     */
    public const ROUTE_NAME_ASSET_LIST = 'customer/asset';

    /**
     * @var string
     */
    public const ROUTE_NAME_ASSET_VIEW_IMAGE = 'customer/asset/view-image';

    /**
     * @var string
     */
    public const ROUTE_NAME_ASSET_UPDATE_RELATIONS = 'customer/asset/update-relations';

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addAssetDetailsRoute($routeCollection);
        $routeCollection = $this->addAssetCreateRoute($routeCollection);
        $routeCollection = $this->addAssetUpdateRoute($routeCollection);
        $routeCollection = $this->addAssetListRoute($routeCollection);
        $routeCollection = $this->addViewAssetImageRoute($routeCollection);
        $routeCollection = $this->addUnassignBusinessUnitRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAssetDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('customer/asset/details', 'SspAssetManagement', 'SspAsset', 'detailsAction');
        $routeCollection->add(static::ROUTE_NAME_ASSET_DETAILS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAssetCreateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('customer/asset/create', 'SspAssetManagement', 'SspAsset', 'createAction');
        $routeCollection->add(static::ROUTE_NAME_ASSET_CREATE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAssetUpdateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('customer/asset/update', 'SspAssetManagement', 'SspAsset', 'updateAction');
        $routeCollection->add(static::ROUTE_NAME_ASSET_UPDATE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addViewAssetImageRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('customer/asset/view-image', 'SspAssetManagement', 'SspImageAsset', 'viewImageAction');
        $routeCollection->add(static::ROUTE_NAME_ASSET_VIEW_IMAGE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAssetListRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('customer/asset', 'SspAssetManagement', 'SspAsset', 'listAction');
        $routeCollection->add(static::ROUTE_NAME_ASSET_LIST, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUnassignBusinessUnitRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildPostRoute('customer/asset/update-relations', 'SspAssetManagement', 'SspAsset', 'updateBusinessUnitRelationAction');
        $routeCollection->add(static::ROUTE_NAME_ASSET_UPDATE_RELATIONS, $route);

        return $routeCollection;
    }
}
