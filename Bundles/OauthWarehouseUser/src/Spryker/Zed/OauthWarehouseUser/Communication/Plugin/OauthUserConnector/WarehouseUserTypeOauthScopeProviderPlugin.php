<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouseUser\Communication\Plugin\OauthUserConnector;

use Generated\Shared\Transfer\UserIdentifierTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthWarehouseUser\Business\OauthWarehouseUserFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthWarehouseUser\OauthWarehouseUserConfig getConfig()
 */
class WarehouseUserTypeOauthScopeProviderPlugin extends AbstractPlugin implements UserTypeOauthScopeProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Provides OAuth scopes related to warehouse users.
     * - Requires `UserIdentifierTransfer.idUser` to be set.
     * - Returns list with OAuth scopes if a provided user is a warehouse user otherwise returns empty list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserIdentifierTransfer $userIdentifierTransfer
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopes(UserIdentifierTransfer $userIdentifierTransfer): array
    {
        return $this->getFacade()->getWarehouseUserTypeOauthScopes($userIdentifierTransfer);
    }
}
