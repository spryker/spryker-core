<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthMerchantUser\Communication\Plugin\OauthUserConnector;

use Generated\Shared\Transfer\UserIdentifierTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthMerchantUser\Business\OauthMerchantUserFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthMerchantUser\OauthMerchantUserConfig getConfig()
 */
class MerchantUserTypeOauthScopeProviderPlugin extends AbstractPlugin implements UserTypeOauthScopeProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Provides OAuth scopes related to merchant users.
     * - Requires `UserIdentifierTransfer.idUser` to be set.
     * - Returns list with OAuth scopes if a provided user is a merchant user otherwise returns empty list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserIdentifierTransfer $userIdentifierTransfer
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopes(UserIdentifierTransfer $userIdentifierTransfer): array
    {
        return $this->getFacade()->getMerchantUserTypeOauthScopes($userIdentifierTransfer);
    }
}
