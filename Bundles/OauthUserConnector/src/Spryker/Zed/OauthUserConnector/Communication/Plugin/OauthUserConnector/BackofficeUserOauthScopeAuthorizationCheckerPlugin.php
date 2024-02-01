<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector\Communication\Plugin\OauthUserConnector;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeAuthorizationCheckerPluginInterface;

/**
 * @method \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig getConfig()
 * @method \Spryker\Zed\OauthUserConnector\Business\OauthUserConnectorFacadeInterface getFacade()
 */
class BackofficeUserOauthScopeAuthorizationCheckerPlugin extends AbstractPlugin implements UserTypeOauthScopeAuthorizationCheckerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns false if the request identity is not a user.
     * - Returns true if user has back-office user scope.
     * - Returns true if user has only basic scopes.
     * - Returns false in other cases.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): bool
    {
        return $this->getFacade()->authorizeByBackofficeUserScope($authorizationRequestTransfer);
    }
}
