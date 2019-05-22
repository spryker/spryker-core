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
     *  - Authenticates customer.
     *  - Reads customer data and provides it for access token.
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
     *  - Reads customer scopes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array;

    /**
     * @api
     *
     * Specification:
     *  - Installs oauth client data.
     *  - Installs oauth scope data.
     *
     * @deprecated Will be removed in the next major.
     *
     * @return void
     */
    public function installCustomerOauthData(): void;

    /**
     * Specification:
     *  - Installs oauth customer scope data.
     *
     * @api
     *
     * @return void
     */
    public function installOauthCustomerScope(): void;

    /**
     * Specification:
     *  - Reads customer client secret.
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
     *  - Reads customer client identifier.
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return string
     */
    public function getCustomerOauthClientIdentifier(): string;
}
