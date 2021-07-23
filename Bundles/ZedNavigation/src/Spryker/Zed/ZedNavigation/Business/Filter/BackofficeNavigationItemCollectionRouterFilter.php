<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\NavigationItemCollectionTransfer;
use Spryker\Zed\ZedNavigation\Dependency\Facade\ZedNavigationToRouterFacadeInterface;

class BackofficeNavigationItemCollectionRouterFilter implements BackofficeNavigationItemCollectionRouterFilterInterface
{
    /**
     * @var \Spryker\Zed\ZedNavigation\Dependency\Facade\ZedNavigationToRouterFacadeInterface
     */
    protected $routerFacade;

    /**
     * @param \Spryker\Zed\ZedNavigation\Dependency\Facade\ZedNavigationToRouterFacadeInterface $routerFacade
     */
    public function __construct(ZedNavigationToRouterFacadeInterface $routerFacade)
    {
        $this->routerFacade = $routerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationItemCollectionTransfer $navigationItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationItemCollectionTransfer
     */
    public function filterNavigationItemCollectionByRouteAccessibility(
        NavigationItemCollectionTransfer $navigationItemCollectionTransfer
    ): NavigationItemCollectionTransfer {
        $routeCollection = $this->routerFacade
            ->getBackofficeRouter()
            ->getRouteCollection();

        $navigationItems = $navigationItemCollectionTransfer->getNavigationItems()->getArrayCopy();

        foreach ($navigationItems as $navigationName => $navigationItem) {
            $route = $routeCollection->get($navigationName);

            if (!$route) {
                unset($navigationItems[$navigationName]);
            }
        }

        return $navigationItemCollectionTransfer->setNavigationItems(new ArrayObject($navigationItems));
    }
}
