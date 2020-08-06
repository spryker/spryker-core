<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Plugin\Navigation;

use Generated\Shared\Transfer\NavigationItemCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemCollectionFilterPluginInterface;

/**
 * @method \Spryker\Zed\Acl\Business\AclFacadeInterface getFacade()
 * @method \Spryker\Zed\Acl\Communication\AclCommunicationFactory getFactory()
 * @method \Spryker\Zed\Acl\AclConfig getConfig()
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface getQueryContainer()
 */
class AclNavigationItemCollectionFilterPlugin extends AbstractPlugin implements NavigationItemCollectionFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if the navigation item can be accessed by the current user.
     * - Returns the navigation items collection without inaccessible items.
     * - Returns the empty collection in case there is no authorized user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationItemCollectionTransfer $navigationItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationItemCollectionTransfer
     */
    public function filter(
        NavigationItemCollectionTransfer $navigationItemCollectionTransfer
    ): NavigationItemCollectionTransfer {
        return $this->getFacade()->filterNavigationItemCollectionByAccessibility($navigationItemCollectionTransfer);
    }
}
