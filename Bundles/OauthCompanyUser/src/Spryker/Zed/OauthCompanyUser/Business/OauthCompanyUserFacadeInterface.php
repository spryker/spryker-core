<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Business;

use Generated\Shared\Transfer\CompanyUserAccessTokenRequestTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;

interface OauthCompanyUserFacadeInterface
{
    /**
     * Specification:
     * - Executes CustomerOauthRequestMapperPlugin stack.
     * - Process token request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function createCompanyUserAccessToken(CustomerTransfer $customerTransfer): OauthResponseTransfer;

    /**
     * Specification:
     * - Validates access token.
     * - Retrieves payload data from token.
     * - Loads customer by id.
     * - Executes CustomerExpanderPlugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserAccessTokenRequestTransfer $companyUserAccessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function getCustomerByAccessToken(CompanyUserAccessTokenRequestTransfer $companyUserAccessTokenRequestTransfer): CustomerResponseTransfer;

    /**
     * Specification:
     *  - Authenticates company user.
     *  - Reads company user data and provides it for access token.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getOauthCompanyUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer;

    /**
     * Specification:
     *  - Reads company user scopes.
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
     *  - Installs oauth scope data.
     *
     * @api
     *
     * @return void
     */
    public function installCompanyUserOauthData(): void;
}
