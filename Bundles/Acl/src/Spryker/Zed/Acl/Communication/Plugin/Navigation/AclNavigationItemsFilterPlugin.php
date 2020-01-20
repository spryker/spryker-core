<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Plugin\Navigation;

use Generated\Shared\Transfer\NavigationItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\NavigationExtension\Dependency\Plugin\NavigationItemFilterPluginInterface;

/**
 * @method \Spryker\Zed\Acl\Business\AclFacadeInterface getFacade()
 * @method \Spryker\Zed\Acl\Communication\AclCommunicationFactory getFactory()
 * @method \Spryker\Zed\Acl\AclConfig getConfig()
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface getQueryContainer()
 */
class AclNavigationItemsFilterPlugin extends AbstractPlugin implements NavigationItemFilterPluginInterface
{
    /**
     * Specification:
     * - Returns true if navigation item is visible in menu.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationItemTransfer $navigationItem
     *
     * @return bool
     */
    public function isVisible(NavigationItemTransfer $navigationItem): bool
    {
        $userFacade = $this->getFactory()->getUserFacade();

        if (!$userFacade->hasCurrentUser()) {
            return true;
        }

        return $this->getFacade()->checkAccess(
            $userFacade->getCurrentUser(),
            $navigationItem->getModule(),
            $navigationItem->getController(),
            $navigationItem->getAction()
        );
    }
}
