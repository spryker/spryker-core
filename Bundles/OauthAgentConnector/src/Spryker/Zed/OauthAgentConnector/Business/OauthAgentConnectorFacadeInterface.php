<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAgentConnector\Business;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;

/**
 * @method \Spryker\Zed\OauthAgentConnector\Business\OauthAgentConnectorBusinessFactory getFactory()
 */
interface OauthAgentConnectorFacadeInterface
{
    /**
     * Specification:
     * - Authenticates an agent.
     * - Reads agent data and provides it for access token.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getAgentOauthUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer;

    /**
     * Specification:
     * - Retrieves agent scopes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array;

    /**
     * Specification:
     * - Installes agent-specific OAuth scopes.
     * - Scopes are defined in `OauthAgentConnectorConfig::getAgentScopes()`.
     * - Skips scope if it already exists in depsistent storage.
     *
     * @api
     *
     * @return void
     */
    public function installAgentOauthScope(): void;
}
