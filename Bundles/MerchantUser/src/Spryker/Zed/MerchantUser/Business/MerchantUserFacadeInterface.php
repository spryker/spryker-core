<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserPasswordResetRequestTransfer;
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
     * - Retrieves user by given email.
     * - Generates reset password token.
     * - Persists the reset password to DB.
     * - Executes plugins that handle user password reset request.
     * - Returns true on success.
     * - Returns false if user was not found or password reset was not saved.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer
     *
     * @return bool
     */
    public function requestPasswordReset(UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer): bool;

    /**
     * Specification:
     * - Validates reset password token.
     * - Returns true if token is valid.
     * - Returns false if token is not valid or expired.
     * - Updates reset password status if expired.
     *
     * @api
     *
     * @param string $token
     *
     * @return bool
     */
    public function isValidPasswordResetToken(string $token): bool;

    /**
     * Specification:
     * - Sets a new user password.
     * - Changes reset password status to `used`.
     * - Returns false if reset password entity does not exist.
     * - Returns true on success.
     *
     * @api
     *
     * @param string $token
     * @param string $password
     *
     * @return bool
     */
    public function setNewPassword(string $token, string $password): bool;
}
