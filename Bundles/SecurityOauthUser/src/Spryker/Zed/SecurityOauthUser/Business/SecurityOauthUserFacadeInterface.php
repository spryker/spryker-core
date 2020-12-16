<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Business;

use Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer;
use Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer;
use Generated\Shared\Transfer\ResourceOwnerRequestTransfer;
use Generated\Shared\Transfer\ResourceOwnerResponseTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface SecurityOauthUserFacadeInterface
{
    /**
     * Specification:
     * - Requests a resource owner using a specified option set.
     * - Requires code field to be set.
     * - Requires state field to be set, contributes to identify the Oauth client during the request.
     * - Runs a stack of `OauthUserClientStrategyPluginInterface` plugins to get suitable Oauth client.
     * - Returns `ResourceOwnerResponseTransfer::isSuccessful = true` in case the request has been handled by appropriate plugin.
     * - Returns `ResourceOwnerResponseTransfer::isSuccessful = false` in case the request has not been handled.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceOwnerResponseTransfer
     */
    public function getResourceOwner(
        ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer
    ): ResourceOwnerResponseTransfer;

    /**
     * Specification:
     * - Checks if the Oauth user is restricted.
     * - Requires user field to be set.
     * - Runs a stack of `OauthUserRestrictionPluginInterface` plugins to check user restrictions.
     * - Returns `OauthUserRestrictionResponseTransfer::isRestricted = true` and messages attached in case the user has been restricted.
     * - Returns `OauthUserRestrictionResponseTransfer::isRestricted = false` and empty messages in case the user has not been restricted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer
     */
    public function isOauthUserRestricted(
        OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer
    ): OauthUserRestrictionResponseTransfer;

    /**
     * Specification:
     * - Resolves an Oauth user.
     * - Uses a strategy to resolve Oauth user.
     * - Returns resolved Oauth user or null otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function resolveOauthUser(UserCriteriaTransfer $userCriteriaTransfer): ?UserTransfer;
}
