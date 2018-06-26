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
     * @api
     *
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getCustomer(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer;

    /**
     * @api
     *
     * {@inheritdoc}
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
     *  Installs inital oauth data
     *
     * @return void
     */
    public function installCustomerOauthData(): void;

    /**
     * @api
     *
     * Specification:
     *  - Reads customer client secret
     *
     * @return string
     */
    public function getCustomerOauthClientSecret(): string;

    /**
     * @api
     *
     * Specification:
     *  - Reads customer client identifier
     *
     * @return string
     */
    public function getCustomerOauthClientIdentifier(): string;
}
