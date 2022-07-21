<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector\Business;

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
}
