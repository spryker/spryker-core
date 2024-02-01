<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector\Business;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;

/**
 * @method \Spryker\Zed\OauthUserConnector\Business\OauthUserConnectorBusinessFactory getFactory()
 */
interface OauthUserConnectorFacadeInterface
{
    /**
     * Specification:
     * - Authenticates user.
     * - Reads user data and provides it for access token.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getOauthUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer;

    /**
     * Specification:
     * - Returns user scopes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array;

    /**
     * Specification:
     * - Installs user-specific OAuth scopes.
     * - Scopes are defined in `OauthUserConnectorConfig::getUserScopes()`.
     * - Skips scope if it already exists in persistent storage.
     *
     * @api
     *
     * @return void
     */
    public function installOauthUserScope(): void;

    /**
     * Specification:
     * - Returns true if the request identity is not a user.
     * - Executes stack of {@link \Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeAuthorizationCheckerPluginInterface} plugins.
     * - Returns true if the request identity is user, and at least one of user`s scopes allows access.
     * - Returns false in other cases.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): bool;

    /**
     * Specification:
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
    public function authorizeByBackofficeUserScope(AuthorizationRequestTransfer $authorizationRequestTransfer): bool;
}
