<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Plugin\Navigation;

use Generated\Shared\Transfer\NavigationItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemFilterPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\Acl\Communication\Plugin\Navigation\AclNavigationItemCollectionFilterPlugin} instead.
 *
 * @method \Spryker\Zed\Acl\Business\AclFacadeInterface getFacade()
 * @method \Spryker\Zed\Acl\Communication\AclCommunicationFactory getFactory()
 * @method \Spryker\Zed\Acl\AclConfig getConfig()
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface getQueryContainer()
 */
class AclNavigationItemFilterPlugin extends AbstractPlugin implements NavigationItemFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns false if user is not authorized.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationItemTransfer $navigationItemTransfer
     *
     * @return bool
     */
    public function isVisible(NavigationItemTransfer $navigationItemTransfer): bool
    {
        $userFacade = $this->getFactory()->getUserFacade();

        if (!$userFacade->hasCurrentUser()) {
            return false;
        }

        return $this->getFacade()->checkAccess(
            $userFacade->getCurrentUser(),
            $navigationItemTransfer->getModule(),
            $navigationItemTransfer->getController(),
            $navigationItemTransfer->getAction()
        );
    }
}
