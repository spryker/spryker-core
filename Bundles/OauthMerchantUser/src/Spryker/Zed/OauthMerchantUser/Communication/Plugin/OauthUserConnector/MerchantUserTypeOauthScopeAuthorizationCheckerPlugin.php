<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthMerchantUser\Communication\Plugin\OauthUserConnector;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeAuthorizationCheckerPluginInterface;

/**
 * @method \Spryker\Zed\OauthMerchantUser\OauthMerchantUserConfig getConfig()
 * @method \Spryker\Zed\OauthMerchantUser\Business\OauthMerchantUserFacadeInterface getFacade()
 */
class MerchantUserTypeOauthScopeAuthorizationCheckerPlugin extends AbstractPlugin implements UserTypeOauthScopeAuthorizationCheckerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Authorizes user by merchant user scopes.
     * - Requires `AuthorizationRequestTransfer.entity` to be set.
     * - Returns false if the request lacks user identity or essential request data.
     * - Returns false if the authenticated user lacks the required merchant user scope.
     * - Checks if the requested path is included in the list of allowed paths.
     * - Checks if the requested path matches any regular expressions in the list of allowed paths.
     * - Returns false if the path is not found in the allowed paths otherwise, the user is authorized.
     * - Returns true if the path is found in the allowed paths; the user is authorized.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): bool
    {
        return $this->getFacade()->authorizeByMerchantUserScope($authorizationRequestTransfer);
    }
}
