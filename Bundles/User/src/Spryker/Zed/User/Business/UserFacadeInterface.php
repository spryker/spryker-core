<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface UserFacadeInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return void
     */
    public function install();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $username
     *
     * @return bool
     */
    public function hasUserByUsername($username);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $username
     *
     * @return bool
     */
    public function hasActiveUserByUsername($username);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\User\Business\UserFacadeInterface::getUserCollection()} instead.
     *
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserByUsername($username);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\User\Business\UserFacadeInterface::getUserCollection()} instead.
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserById($idUser);

    /**
     * Specification:
     * - Returns user by id if it exists.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\User\Business\UserFacadeInterface::getUserCollection()} instead.
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findUserById(int $idUser): ?UserTransfer;

    /**
     * Specification:
     * - Returns User transfer found by criteria.
     * - Returns null otherwise.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\User\Business\UserFacadeInterface::getUserCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findUser(UserCriteriaTransfer $userCriteriaTransfer): ?UserTransfer;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\User\Business\UserFacadeInterface::getUserCollection()} instead.
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getActiveUserById($idUser);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\User\Business\UserFacadeInterface::createUser()} instead.
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string $password
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function addUser($firstName, $lastName, $username, $password);

    /**
     * Specification:
     * - Creates user from `UserTransfer`.
     * - Executes a stack of {@link \Spryker\Zed\UserExtension\Dependency\Plugin\UserPreSavePluginInterface} plugins.
     * - Executes a stack of {@link \Spryker\Zed\UserExtension\Dependency\Plugin\UserExpanderPluginInterface} plugins.
     * - Executes a stack of {@link \Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface} plugins.
     * - Throws exception if username exist.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function createUser(UserTransfer $userTransfer): UserTransfer;

    /**
     * Specification:
     * - Updates User with passed `UserTransfer` data.
     * - Executes a stack of {@link \Spryker\Zed\UserExtension\Dependency\Plugin\UserPreSavePluginInterface} plugins.
     * - Executes a stack of {@link \Spryker\Zed\UserExtension\Dependency\Plugin\UserExpanderPluginInterface} plugins.
     * - Executes a stack of {@link \Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface} plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function updateUser(UserTransfer $user);

    /**
     * Specification:
     * - Sets User to the session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return mixed
     */
    public function setCurrentUser(UserTransfer $user);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return bool
     */
    public function hasCurrentUser();

    /**
     * Specification:
     * - Verifies that the password matches the hash.
     *
     * @api
     *
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function isValidPassword($password, $hash);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return bool
     */
    public function isSystemUser(UserTransfer $user);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CollectionTransfer
     */
    public function getSystemUsers();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function removeUser($idUser);

    /**
     * Specification:
     * - Finds user by provided ID.
     * - Throws {@link \Spryker\Zed\User\Business\Exception\UserNotFoundException} in case user was not found by provided ID.
     * - Sets `active` status to a user.
     * - Persists updated user.
     * - If enabled by configuration, executes {@link \Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface} plugin stack.
     * - Returns `true` in case user was successfully persisted, `false` otherwise.
     *
     * @api
     *
     * @param int $idUser
     *
     * @return bool
     */
    public function activateUser($idUser);

    /**
     * Specification:
     * - Finds user by provided ID.
     * - Throws {@link \Spryker\Zed\User\Business\Exception\UserNotFoundException} in case user was not found by provided ID.
     * - Sets `blocked` status to a user.
     * - Persists updated user.
     * - If enabled by configuration, executes {@link \Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface} plugin stack.
     * - Returns `true` in case user was successfully persisted, `false` otherwise.
     *
     * @api
     *
     * @param int $idUser
     *
     * @return bool
     */
    public function deactivateUser($idUser);

    /**
     * Specification:
     * - Requires MailTransfer.recipients and MailTransfer.recipients.email to be set.
     * - Expands the given mail transfer with an additional user data.
     *
     * @api
     *
     * @deprecated Will be removed without replacement. Handling of user password reset mail is implemented in UserPasswordReset module.
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function expandMailWithUserData(MailTransfer $mailTransfer): MailTransfer;

    /**
     * Specification:
     * - Fetches collection of Users from the storage.
     * - Uses `UserCriteriaTransfer.UserConditions.userIds` to filter users by userIds.
     * - Uses `UserCriteriaTransfer.UserConditions.usernames` to filter users by usernames (emails).
     * - Uses `UserCriteriaTransfer.UserConditions.statuses` to filter users by statuses.
     * - Uses `UserCriteriaTransfer.UserConditions.uuids` to filter users by uuids if `spy_users.uuid` column exists.
     * - Executes a stack of {@link \Spryker\Zed\UserExtension\Dependency\Plugin\UserQueryCriteriaExpanderPluginInterface} plugins.
     * - Executes a stack of {@link \Spryker\Zed\UserExtension\Dependency\Plugin\UserExpanderPluginInterface} plugins.
     * - Executes a stack of {@link \Spryker\Zed\UserExtension\Dependency\Plugin\UserTransferExpanderPluginInterface} plugins for BC-reasons.
     * - If `UserCriteriaTransfer.UserConditions.throwException` is set to `true`, method will throw {@link \Spryker\Zed\User\Business\Exception\UserNotFoundException} in case of no user was found.
     * - Returns `UserCollectionTransfer` filled with found users.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function getUserCollection(UserCriteriaTransfer $userCriteriaTransfer): UserCollectionTransfer;
}
