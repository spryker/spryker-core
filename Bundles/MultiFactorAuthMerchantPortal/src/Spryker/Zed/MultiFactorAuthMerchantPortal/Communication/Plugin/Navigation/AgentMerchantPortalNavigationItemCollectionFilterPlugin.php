<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Plugin\Navigation;

use ArrayObject;
use Generated\Shared\Transfer\NavigationItemCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemCollectionFilterPluginInterface;

/**
 * @method \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\MultiFactorAuthMerchantPortalCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuthMerchantPortal\MultiFactorAuthMerchantPortalConfig getConfig()
 */
class AgentMerchantPortalNavigationItemCollectionFilterPlugin extends AbstractPlugin implements NavigationItemCollectionFilterPluginInterface
{
    /**
     * @uses {@link \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Controller\AgentUserManagementController::setUpAction()}
     *
     * @var string
     */
    protected const NAVIGATION_ITEM_NAME = 'multi-factor-auth-merchant-portal:agent-user-management:set-up';

    /**
     * {@inheritDoc}
     * - Checks if the `Set up Multi-Factor Authentication` page can be accessed in Agent Merchant Portal drop-down menu.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NavigationItemCollectionTransfer $navigationItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationItemCollectionTransfer
     */
    public function filter(NavigationItemCollectionTransfer $navigationItemCollectionTransfer): NavigationItemCollectionTransfer
    {
        $navigationItems = $navigationItemCollectionTransfer->getNavigationItems()->getArrayCopy();

        if ($this->getFactory()->getUserFacade()->hasCurrentUser() === false) {
            return $navigationItemCollectionTransfer;
        }

        $currentUser = $this->getFactory()->getUserFacade()->getCurrentUser();

        foreach ($navigationItems as $navigationName => $navigationItem) {
            if (
                $navigationName === static::NAVIGATION_ITEM_NAME &&
                (count($this->getFactory()->getUserMultiFactorAuthPlugins()) === 0 || !$currentUser->getIsMerchantAgent())
            ) {
                unset($navigationItems[$navigationName]);
            }
        }

        return $navigationItemCollectionTransfer->setNavigationItems(new ArrayObject($navigationItems));
    }
}
