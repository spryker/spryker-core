<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;

/**
 * Executes authorization check based on user OAuth scopes.
 */
interface UserTypeOauthScopeAuthorizationCheckerPluginInterface
{
    /**
     * Specification:
     * - Processes an authorization request.
     * - Returns true if user has OAuth scope that authorizes an action.
     * - Returns false if user is not authorized.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): bool;
}
