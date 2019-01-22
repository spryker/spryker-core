<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business\Model;

use Generated\Shared\Transfer\CollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Orm\Zed\User\Persistence\SpyUser;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\User\Business\Exception\UsernameExistsException;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;
use Spryker\Zed\User\Persistence\UserQueryContainerInterface;
use Spryker\Zed\User\UserConfig;

class User implements UserInterface
{
    /**
     * @var \Spryker\Zed\User\Persistence\UserQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\User\UserConfig
     */
    protected $settings;

    /**
     * @var \Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface[]
     */
    protected $userPostSavePlugins;

    use TransactionTrait;

    /**
     * @param \Spryker\Zed\User\Persistence\UserQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\User\UserConfig $settings
     * @param \Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface[] $userPostSavePlugins
     */
    public function __construct(
        UserQueryContainerInterface $queryContainer,
        UserConfig $settings,
        array $userPostSavePlugins = []
    ) {
        $this->queryContainer = $queryContainer;
        $this->settings = $settings;
        $this->userPostSavePlugins = $userPostSavePlugins;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string $password
     *
     * @throws \Spryker\Zed\User\Business\Exception\UsernameExistsException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function addUser($firstName, $lastName, $username, $password)
    {
        $userCheck = $this->hasUserByUsername($username);

        if ($userCheck === true) {
            throw new UsernameExistsException();
        }

        $transferUser = new UserTransfer();
        $transferUser->setFirstName($firstName);
        $transferUser->setLastName($lastName);
        $transferUser->setUsername($username);
        $transferUser->setPassword($password);

        return $this->save($transferUser);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @throws \Spryker\Zed\User\Business\Exception\UsernameExistsException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function createUser(UserTransfer $userTransfer): UserTransfer
    {
        $userCheck = $this->hasUserByUsername($userTransfer->getUsername());

        if ($userCheck === true) {
            throw new UsernameExistsException(
                sprintf('Username %s already exist.', $userTransfer->getUsername())
            );
        }

        return $this->save($userTransfer);
    }

    /**
     * @param string $password
     *
     * @return string
     */
    public function encryptPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function validatePassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function save(UserTransfer $userTransfer)
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($userTransfer): UserTransfer {
            return $this->executeSaveTransaction($userTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function executeSaveTransaction(UserTransfer $userTransfer): UserTransfer
    {
        if ($userTransfer->getIdUser() !== null) {
            $userEntity = $this->getEntityUserById($userTransfer->getIdUser());
        } else {
            $userEntity = new SpyUser();
        }

        $modifiedUser = $userTransfer->modifiedToArray();

        unset($modifiedUser[UserTransfer::PASSWORD]);

        $userEntity->fromArray($modifiedUser);

        $password = $userTransfer->getPassword();
        if (!empty($password) && $this->isRawPassword($password)) {
            $userEntity->setPassword($this->encryptPassword($password));
        }

        $userEntity->save();
        $userTransfer = $this->entityToTransfer($userEntity);
        $userTransfer = $this->executePostSavePlugins($userTransfer);

        return $userTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function executePostSavePlugins(UserTransfer $userTransfer): UserTransfer
    {
        foreach ($this->userPostSavePlugins as $postSavePlugin) {
            $userTransfer = $postSavePlugin->postSave($userTransfer);
        }

        return $userTransfer;
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function removeUser($idUser)
    {
        $user = $this->getUserById($idUser);
        $user->setStatus('deleted');

        return $this->save($user);
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    private function isRawPassword($password)
    {
        $passwordInfo = password_get_info($password);

        return $passwordInfo['algo'] === 0;
    }

    /**
     * @param string $username
     *
     * @return bool
     */
    public function hasUserByUsername($username)
    {
        $amount = $this->queryContainer->queryUserByUsername($username)->count();

        return $amount > 0;
    }

    /**
     * @param string $username
     *
     * @return bool
     */
    public function hasActiveUserByUsername($username)
    {
        $amount = $this->queryContainer->queryUserByUsername($username)
            ->filterByStatus(SpyUserTableMap::COL_STATUS_ACTIVE)->count();

        return $amount > 0;
    }

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function hasUserById($idUser)
    {
        $amount = $this->queryContainer->queryUserById($idUser)->count();

        return $amount > 0;
    }

    /**
     * @param string $username
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserByUsername($username)
    {
        $entity = $this->queryContainer->queryUserByUsername($username)->findOne();

        if ($entity === null) {
            throw new UserNotFoundException();
        }

        return $this->entityToTransfer($entity);
    }

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserById($id)
    {
        $entity = $this->queryContainer
            ->queryUserById($id)
            ->findOne();

        if ($entity === null) {
            throw new UserNotFoundException();
        }

        return $this->entityToTransfer($entity);
    }

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findUserById(int $id): ?UserTransfer
    {
        $entity = $this->queryContainer
            ->queryUserById($id)
            ->findOne();

        if ($entity === null) {
            return null;
        }

        return $this->entityToTransfer($entity);
    }

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getActiveUserById($id)
    {
        $entity = $this->queryContainer
            ->queryUserById($id)
            ->filterByStatus(SpyUserTableMap::COL_STATUS_ACTIVE)
            ->findOne();

        if ($entity === null) {
            throw new UserNotFoundException();
        }

        return $this->entityToTransfer($entity);
    }

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Orm\Zed\User\Persistence\SpyUser
     */
    public function getEntityUserById($id)
    {
        $entity = $this->queryContainer->queryUserById($id)->findOne();

        if ($entity === null) {
            throw new UserNotFoundException();
        }

        return $entity;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return bool
     */
    public function isSystemUser(UserTransfer $user)
    {
        $systemUsers = $this->settings->getSystemUsers();

        return in_array($user->getUsername(), $systemUsers);
    }

    /**
     * @return \Generated\Shared\Transfer\CollectionTransfer
     */
    public function getSystemUsers()
    {
        $systemUser = $this->settings->getSystemUsers();
        $collection = new CollectionTransfer();

        foreach ($systemUser as $username) {
            $transferUser = new UserTransfer();

            // TODO why setting the id? why is everything the username?
            $transferUser->setIdUser(0);

            $transferUser->setFirstName($username)
                ->setLastName($username)
                ->setUsername($username)
                ->setPassword($username);

            $collection->addUser($transferUser);
        }

        return $collection;
    }

    /**
     * @param \Orm\Zed\User\Persistence\SpyUser $userEntity
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function entityToTransfer(SpyUser $userEntity)
    {
        $userTransfer = new UserTransfer();
        $userTransfer->fromArray($userEntity->toArray(), true);

        return $userTransfer;
    }

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function activateUser($idUser)
    {
        $userEntity = $this->queryContainer->queryUserById($idUser)->findOne();
        $userEntity->setStatus(SpyUserTableMap::COL_STATUS_ACTIVE);
        $rowsAffected = $userEntity->save();

        return $rowsAffected > 0;
    }

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function deactivateUser($idUser)
    {
        $userEntity = $this->queryContainer->queryUserById($idUser)->findOne();
        $userEntity->setStatus(SpyUserTableMap::COL_STATUS_BLOCKED);
        $rowsAffected = $userEntity->save();

        return $rowsAffected > 0;
    }
}
