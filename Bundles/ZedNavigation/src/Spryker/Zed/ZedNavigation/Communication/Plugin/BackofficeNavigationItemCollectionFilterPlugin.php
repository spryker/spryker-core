<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Communication\Plugin;

use Generated\Shared\Transfer\NavigationItemCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemCollectionFilterPluginInterface;

/**
 * @method \Spryker\Zed\ZedNavigation\Business\ZedNavigationFacadeInterface getFacade()
 * @method \Spryker\Zed\ZedNavigation\ZedNavigationConfig getConfig()
 * @method \Spryker\Zed\ZedNavigation\Communication\ZedNavigationCommunicationFactory getFactory()
 */
class BackofficeNavigationItemCollectionFilterPlugin extends AbstractPlugin implements NavigationItemCollectionFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if the navigation item can be accessed into Backoffice Router.
     * - Returns the navigation items collection without non-existed items into Backoffice Router.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationItemCollectionTransfer $navigationItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationItemCollectionTransfer
     */
    public function filter(NavigationItemCollectionTransfer $navigationItemCollectionTransfer): NavigationItemCollectionTransfer
    {
        return $this->getFacade()
            ->filterNavigationItemCollectionByBackofficeRouteAccessibility($navigationItemCollectionTransfer);
    }
}
