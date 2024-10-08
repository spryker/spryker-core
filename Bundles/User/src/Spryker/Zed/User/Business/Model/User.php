<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business\Model;

use Generated\Shared\Transfer\CollectionTransfer;
use Generated\Shared\Transfer\UserCollectionResponseTransfer;
use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Orm\Zed\User\Persistence\SpyUser;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\User\Business\Exception\PasswordEncryptionFailedException;
use Spryker\Zed\User\Business\Exception\UsernameExistsException;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;
use Spryker\Zed\User\Persistence\UserQueryContainerInterface;
use Spryker\Zed\User\UserConfig;

class User implements UserInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    public const USER_BUNDLE_SESSION_KEY = 'user';

    /**
     * @var \Spryker\Zed\User\Persistence\UserQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    protected $session;

    /**
     * @var \Spryker\Zed\User\UserConfig
     */
    protected $userConfig;

    /**
     * @var list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface>
     */
    protected $userPostSavePlugins;

    /**
     * @var list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPreSavePluginInterface>
     */
    protected $userPreSavePlugins;

    /**
     * @deprecated Use {@link \Spryker\Zed\User\Business\Model\User::$userExpanderPlugins} instead.
     *
     * @var list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserTransferExpanderPluginInterface>
     */
    protected $userTransferExpanderPlugins;

    /**
     * @var list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserExpanderPluginInterface>
     */
    protected array $userExpanderPlugins;

    /**
     * @var list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPostCreatePluginInterface>
     */
    protected array $userPostCreatePlugins;

    /**
     * @var list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPostUpdatePluginInterface>
     */
    protected array $userPostUpdatePlugins;

    /**
     * @param \Spryker\Zed\User\Persistence\UserQueryContainerInterface $queryContainer
     * @param \Spryker\Client\Session\SessionClientInterface $session
     * @param \Spryker\Zed\User\UserConfig $userConfig
     * @param list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface> $userPostSavePlugins
     * @param list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPreSavePluginInterface> $userPreSavePlugins
     * @param list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserTransferExpanderPluginInterface> $userTransferExpanderPlugins
     * @param list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserExpanderPluginInterface> $userExpanderPlugins
     * @param list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPostCreatePluginInterface> $userPostCreatePlugins
     * @param list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPostUpdatePluginInterface> $userPostUpdatePlugins
     */
    public function __construct(
        UserQueryContainerInterface $queryContainer,
        SessionClientInterface $session,
        UserConfig $userConfig,
        array $userPostSavePlugins = [],
        array $userPreSavePlugins = [],
        array $userTransferExpanderPlugins = [],
        array $userExpanderPlugins = [],
        array $userPostCreatePlugins = [],
        array $userPostUpdatePlugins = []
    ) {
        $this->queryContainer = $queryContainer;
        $this->session = $session;
        $this->userConfig = $userConfig;
        $this->userPostSavePlugins = $userPostSavePlugins;
        $this->userPreSavePlugins = $userPreSavePlugins;
        $this->userTransferExpanderPlugins = $userTransferExpanderPlugins;
        $this->userExpanderPlugins = $userExpanderPlugins;
        $this->userPostCreatePlugins = $userPostCreatePlugins;
        $this->userPostUpdatePlugins = $userPostUpdatePlugins;
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
        $userCheck = $this->hasUserByUsername($userTransfer->getUsernameOrFail());

        if ($userCheck === true) {
            throw new UsernameExistsException(
                sprintf('Username %s already exist.', $userTransfer->getUsername()),
            );
        }

        return $this->handleUserCreateTransaction($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function updateUser(UserTransfer $userTransfer): UserTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($userTransfer) {
            return $this->executeUserUpdateTransaction($userTransfer);
        });
    }

    /**
     * @param string $password
     *
     * @return string|false
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
     * @throws \Spryker\Zed\User\Business\Exception\PasswordEncryptionFailedException
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

        $userTransfer = $this->executePreSavePlugins($userTransfer);
        $modifiedUser = $userTransfer->modifiedToArray();

        unset($modifiedUser[UserTransfer::PASSWORD]);

        $userEntity->fromArray($modifiedUser);

        $password = $userTransfer->getPassword();
        if ($password && $this->isRawPassword($password)) {
            $passwordEncrypted = $this->encryptPassword($password);
            if ($passwordEncrypted === false) {
                throw new PasswordEncryptionFailedException();
            }

            $userEntity->setPassword($passwordEncrypted);
        }

        $userEntity->save();
        $userTransfer = $this->entityToTransfer(
            $userEntity,
            (new UserTransfer())->fromArray($userTransfer->toArray()),
        );
        $userTransfer = $this->executePostSavePlugins($userTransfer);

        return $userTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function executePreSavePlugins(UserTransfer $userTransfer): UserTransfer
    {
        foreach ($this->userPreSavePlugins as $preSavePlugin) {
            $userTransfer = $preSavePlugin->preSave($userTransfer);
        }

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
        $userTransfer = (new UserTransfer())
            ->setIdUser($idUser)
            ->setStatus('deleted');

        $userTransfer = $this->save($userTransfer);

        return $this->executeUserPostUpdatePlugins($userTransfer);
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    private function isRawPassword($password)
    {
        $passwordInfo = password_get_info($password);

        return $passwordInfo['algoName'] === 'unknown';
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
     * @deprecated Use {@link \Spryker\Zed\User\Business\Reader\UserReader::getUserCollection()} instead.
     *
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
     * @deprecated Use {@link \Spryker\Zed\User\Business\Reader\UserReader::getUserCollection()} instead.
     *
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
     * @deprecated Use {@link \Spryker\Zed\User\Business\Model\User::findUser()} instead.
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findUserById(int $id): ?UserTransfer
    {
        return $this->findUserByIdUser($id);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\User\Business\Reader\UserReader::getUserCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findUser(UserCriteriaTransfer $userCriteriaTransfer): ?UserTransfer
    {
        if ($userCriteriaTransfer->getIdUser() !== null) {
            return $this->findUserByIdUser($userCriteriaTransfer->getIdUser());
        }

        if ($userCriteriaTransfer->getEmail() !== null) {
            return $this->findUserByEmail($userCriteriaTransfer->getEmail());
        }

        return null;
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findUserByIdUser(int $idUser): ?UserTransfer
    {
        $userEntity = $this->queryContainer
            ->queryUserById($idUser)
            ->findOne();

        if (!$userEntity) {
            return null;
        }

        return $this->entityToTransfer($userEntity);
    }

    /**
     * @param string $email
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findUserByEmail(string $email): ?UserTransfer
    {
        $userEntity = $this->queryContainer
            ->queryUserByUsername($email)
            ->findOne();

        if (!$userEntity) {
            return null;
        }

        return $this->entityToTransfer($userEntity);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\User\Business\Reader\UserReader::getUserCollection()} instead.
     *
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
     * @return mixed
     */
    public function setCurrentUser(UserTransfer $user)
    {
        $key = $this->createUserKey();

        return $this->session->set($key, clone $user);
    }

    /**
     * @return bool
     */
    public function hasCurrentUser()
    {
        $user = $this->readUserFromSession();

        return $user !== null;
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function readUserFromSession()
    {
        $key = $this->createUserKey();

        if (!$this->session->has($key)) {
            return null;
        }

        return $this->session->get($key);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $user
     *
     * @return bool
     */
    public function isSystemUser(UserTransfer $user)
    {
        $systemUsers = $this->userConfig->getSystemUsers();

        return in_array($user->getUsername(), $systemUsers);
    }

    /**
     * @return \Generated\Shared\Transfer\CollectionTransfer
     */
    public function getSystemUsers()
    {
        $systemUser = $this->userConfig->getSystemUsers();
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
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser()
    {
        $user = $this->readUserFromSession();

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return clone $user;
    }

    /**
     * @param \Orm\Zed\User\Persistence\SpyUser $userEntity
     * @param \Generated\Shared\Transfer\UserTransfer|null $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function entityToTransfer(SpyUser $userEntity, ?UserTransfer $userTransfer = null)
    {
        if ($userTransfer === null) {
            $userTransfer = new UserTransfer();
        }

        $userTransfer->fromArray($userEntity->toArray(), true);

        $userTransfer = $this->executeUserExpanderPlugins($userTransfer);
        $userTransfer = $this->executeUserTransferExpanderPlugins($userTransfer);

        return $userTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function executeUserExpanderPlugins(UserTransfer $userTransfer): UserTransfer
    {
        $userCollectionTransfer = (new UserCollectionTransfer())->addUser($userTransfer);
        foreach ($this->userExpanderPlugins as $userExpanderPlugin) {
            $userCollectionTransfer = $userExpanderPlugin->expand($userCollectionTransfer);
        }

        return $userCollectionTransfer->getUsers()->getIterator()->current();
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\User\Business\Model\User::executeUserExpanderPlugins()} instead.
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function executeUserTransferExpanderPlugins(UserTransfer $userTransfer): UserTransfer
    {
        foreach ($this->userTransferExpanderPlugins as $userTransferExpanderPlugin) {
            $userTransfer = $userTransferExpanderPlugin->expandUserTransfer($userTransfer);
        }

        return $userTransfer;
    }

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function activateUser($idUser)
    {
        return $this->updateUserStatus($idUser, SpyUserTableMap::COL_STATUS_ACTIVE);
    }

    /**
     * @param int $idUser
     *
     * @return bool
     */
    public function deactivateUser($idUser)
    {
        return $this->updateUserStatus($idUser, SpyUserTableMap::COL_STATUS_BLOCKED);
    }

    /**
     * @param int $idUser
     * @param string $status
     *
     * @return bool
     */
    protected function updateUserStatus(int $idUser, string $status): bool
    {
        $userEntity = $this->queryUserById($idUser);
        $userEntity->setStatus($status);
        $rowsAffected = $userEntity->save();
        $userTransfer = $this->entityToTransfer($userEntity);

        if ($this->userConfig->isPostSavePluginsEnabledAfterUserStatusChange()) {
            $this->executePostSavePlugins($userTransfer);
        }

        $this->executeUserPostUpdatePlugins($userTransfer);

        return $rowsAffected > 0;
    }

    /**
     * @param int $idUser
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return \Orm\Zed\User\Persistence\SpyUser
     */
    protected function queryUserById(int $idUser): SpyUser
    {
        $userEntity = $this->queryContainer->queryUserById($idUser)->findOne();
        if (!$userEntity) {
            throw new UserNotFoundException();
        }

        return $userEntity;
    }

    /**
     * @return string
     */
    protected function createUserKey()
    {
        return sprintf('%s:currentUser', static::USER_BUNDLE_SESSION_KEY);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function handleUserCreateTransaction(UserTransfer $userTransfer): UserTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($userTransfer) {
            $userTransfer = $this->executeSaveTransaction($userTransfer);

            return $this->executePostCreateTransaction($userTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function executePostCreateTransaction(UserTransfer $userTransfer): UserTransfer
    {
        $userCollectionResponseTransfer = (new UserCollectionResponseTransfer())->addUser($userTransfer);
        $userCollectionResponseTransfer = $this->executeUserPostCreatePlugins($userCollectionResponseTransfer);

        return $userCollectionResponseTransfer->getUsers()->offsetGet(0);
    }

    /**
     * @param \Generated\Shared\Transfer\UserCollectionResponseTransfer $userCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionResponseTransfer
     */
    protected function executeUserPostCreatePlugins(
        UserCollectionResponseTransfer $userCollectionResponseTransfer
    ): UserCollectionResponseTransfer {
        foreach ($this->userPostCreatePlugins as $userPostCreatePlugin) {
            $userCollectionResponseTransfer = $userPostCreatePlugin->postCreate($userCollectionResponseTransfer);
        }

        return $userCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function executeUserUpdateTransaction(UserTransfer $userTransfer): UserTransfer
    {
        $userTransfer = $this->executeSaveTransaction($userTransfer);

        return $this->executeUserPostUpdatePlugins($userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function executeUserPostUpdatePlugins(UserTransfer $userTransfer): UserTransfer
    {
        $userCollectionResponseTransfer = (new UserCollectionResponseTransfer())->addUser($userTransfer);

        foreach ($this->userPostUpdatePlugins as $userPostUpdatePlugin) {
            $userCollectionResponseTransfer = $userPostUpdatePlugin->postUpdate($userCollectionResponseTransfer);
        }

        return $userCollectionResponseTransfer->getUsers()->getIterator()->current();
    }
}
