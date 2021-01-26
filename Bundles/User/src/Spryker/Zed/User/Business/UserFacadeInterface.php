<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business;

use Generated\Shared\Transfer\MailTransfer;
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
     * @deprecated Use {@link findUser()} instead.
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
     *  - Creates user from user transfer.
     *  - Executes user post save plugins.
     *  - Throws exception if username exist.
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
     * - TODO: Add method specification.
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
     * - TODO: Add method specification.
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
     * - TODO: Add method specification.
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
     * - TODO: Add method specification.
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
     * - TODO: Add method specification.
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
}
