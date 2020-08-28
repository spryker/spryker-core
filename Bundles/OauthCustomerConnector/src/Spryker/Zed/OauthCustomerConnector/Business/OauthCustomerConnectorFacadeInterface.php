<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCustomerConnector\Business;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;

/**
 * @method \Spryker\Zed\OauthCustomerConnector\Business\OauthCustomerConnectorBusinessFactory getFactory()
 */
interface OauthCustomerConnectorFacadeInterface
{
    /**
     * Specification:
     * - Authenticates customer.
     * - Reads customer data and provides it for access token.
     * - Executes `OauthCustomerIdentifierExpanderPluginInterface` plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getCustomerOauthUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer;

    /**
     * Specification:
     *  - Authenticates impersonated customer.
     *  - Reads customer data by `customer_reference` and provides it for access token.
     *  - Executes `OauthCustomerIdentifierExpanderPluginInterface` plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getCustomerImpersonationOauthUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer;

    /**
     * Specification:
     * - Returns customer scopes.
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
     * - Returns customer impersonation scopes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function getCustomerImpersonationScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array;

    /**
     * Specification:
     * - Installs oauth client data.
     * - Installs oauth scope data.
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return void
     */
    public function installCustomerOauthData(): void;

    /**
     * Specification:
     * - Installs customer-specific OAuth scopes.
     * - Scopes are defined in `OauthCustomerConnectorConfig::getCustomerScopes()`, `OauthCustomerConnectorConfig::getCustomerImpersonationScopes()`.
     * - Skips scope if it already exists in persistent storage.
     *
     * @api
     *
     * @return void
     */
    public function installOauthCustomerScope(): void;

    /**
     * Specification:
     * - Reads customer client secret.
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return string
     */
    public function getCustomerOauthClientSecret(): string;

    /**
     * Specification:
     * - Reads customer client identifier.
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return string
     */
    public function getCustomerOauthClientIdentifier(): string;
}
