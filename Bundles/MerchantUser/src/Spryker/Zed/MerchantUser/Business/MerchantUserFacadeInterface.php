<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer;
use Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface MerchantUserFacadeInterface
{
    /**
     * Specification:
     * - Creates a new merchant user entity.
     * - Persists the entity to DB.
     * - Returns merchant user response with newly created merchant user transfer inside.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function createMerchantUser(MerchantUserTransfer $merchantUserTransfer): MerchantUserResponseTransfer;

    /**
     * Specification:
     * - Updates MerchantUser with passed MerchantUser transfer data.
     * - Updates User when MerchantUserTransfer.user is provided.
     * - Resets password for a User, if the User is activated.
     * - Returns MerchantUserResponse transfer with updated MerchantUser transfer inside.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function updateMerchantUser(MerchantUserTransfer $merchantUserTransfer): MerchantUserResponseTransfer;

    /**
     * Specification:
     * - Requires MerchantUserTransfer.idUser.
     * - Deletes MerchantUser by passed MerchantUser transfer data.
     * - Sets MerchantUserResponseTransfer.isSuccessful=true if merchant user was deleted.
     * - Sets MerchantUserResponseTransfer.isSuccessful=false if merchant user cannot be deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function deleteMerchantUser(MerchantUserTransfer $merchantUserTransfer): MerchantUserResponseTransfer;

    /**
     * Specification:
     * - Disables MerchantUsers that match the given criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return void
     */
    public function disableMerchantUsers(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): void;

    /**
     * Specification:
     * - Returns MerchantUser transfer found by criteria.
     * - Returns null otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function findMerchantUser(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): ?MerchantUserTransfer;

    /**
     * Specification:
     * - Requires UserCriteriaTransfer.email.
     * - Returns User transfer found by email.
     * - Returns null otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findUser(UserCriteriaTransfer $userCriteriaTransfer): ?UserTransfer;

    /**
     * Specification:
     * - Returns effective merchant user.
     * - Should not be used when user is not logged in.
     * - Throws MerchantUserNotFoundException exception if no Merchant users exist for current logged in user.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function getCurrentMerchantUser(): MerchantUserTransfer;

    /**
     * Specification:
     * - Authenticates a merchant user.
     * - Updates User's last login date.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return void
     */
    public function authenticateMerchantUser(MerchantUserTransfer $merchantUserTransfer): void;

    /**
     * Specification:
     * - Requires `OauthUserRestrictionRequestTransfer.user.username` to be provided.
     * - Checks if the Oauth user is restricted.
     * - When the user has a relation to the merchant he is considered as restricted.
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
}
