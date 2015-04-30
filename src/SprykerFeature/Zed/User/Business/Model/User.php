<?php

namespace SprykerFeature\Zed\User\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Library\Copy;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Shared\User\Transfer\User as TransferUser;
use SprykerFeature\Shared\User\Transfer\UserCollection;
use SprykerFeature\Zed\User\UserConfig;
use SprykerFeature\Zed\User\Persistence\Propel\SpyUserUser;
use SprykerFeature\Zed\User\Persistence\UserQueryContainer;
use SprykerFeature\Zed\User\Business\Exception\UserNotFoundException;
use SprykerFeature\Zed\User\Business\Exception\UsernameExistsException;

class User implements UserInterface
{

    const USER_BUNDLE_SESSION_KEY = 'user';

    /**
     * @var UserQueryContainer
     */
    protected $queryContainer;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var UserConfig
     */
    protected $settings;

    /**
     * @param UserQueryContainer $queryContainer
     * @param LocatorLocatorInterface $locator
     * @param UserConfig $settings
     */
    public function __construct(
        UserQueryContainer $queryContainer,
        LocatorLocatorInterface $locator,
        UserConfig $settings
    ) {
        $this->queryContainer = $queryContainer;
        $this->locator = $locator;
        $this->settings = $settings;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string $password
     *
     * @return TransferUser
     * @throws UsernameExistsException
     */
    public function addUser($firstName, $lastName, $username, $password)
    {
        $userCheck = $this->hasUserByUsername($username);

        if ($userCheck === true) {
            throw new UsernameExistsException();
        }

        $transferUser = new \Generated\Shared\Transfer\UserUserTransfer();
        $transferUser->setFirstName($firstName);
        $transferUser->setLastName($lastName);
        $transferUser->setUsername($username);
        $transferUser->setPassword($password);

        return $this->save($transferUser);
    }

    /**
     * @param string $password
     *
     * @return string
     */
    public function encryptPassword($password)
    {
        return base64_encode(password_hash($password, PASSWORD_BCRYPT));
    }

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function validatePassword($password, $hash)
    {
        return password_verify($password, base64_decode($hash));
    }

    /**
     * @param TransferUser $data
     *
     * @return TransferUser
     * @throws UserNotFoundException
     */
    public function save(TransferUser $data)
    {
        if ($data->getIdUserUser() !== null && $this->getUserById($data->getIdUserUser()) === null) {
            throw new UserNotFoundException();
        }

        if ($data->getIdUserUser() !== null) {
            $entity = $this->getEntityUserById($data->getIdUserUser());
        } else {
            $entity = $this->locator->user()->entitySpyUserUser();
        }

        $entity->setFirstName($data->getFirstName());
        $entity->setLastName($data->getLastName());
        $entity->setUsername($data->getUsername());

        $password = $data->getPassword();
        if (!empty($password) && true === $this->isRawPassword($data->getPassword())) {
            $entity->setPassword($this->encryptPassword($data->getPassword()));
        }

        $entity->save();
        $transfer = $this->entityToTransfer($entity);

        return $transfer;
    }

    /**
     * @param $idUser
     *
     * @return TransferUser
     */
    public function removeUser($idUser)
    {
        $user = $this->getUserById($idUser);
        $user->setStatus('deleted');

        return $this->save($user);
    }

    /**
     * @return UserCollection
     * @throws UserNotFoundException
     */
    public function getUsers()
    {
        $users = $this->queryContainer->queryUsers()->find();

        if (false === ($users instanceof ObjectCollection)) {
            throw new UserNotFoundException();
        }

        $userCollection = $this->locator->user()->transferUserCollection();

        return Copy::entityCollectionToTransferCollection($userCollection, $users, false);
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
     * @return TransferUser
     * @throws UserNotFoundException
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
     * @return TransferUser
     * @throws UserNotFoundException
     */
    public function getUserById($id)
    {
        $entity = $this->queryContainer->queryUserById($id)->findOne();

        if ($entity === null) {
            throw new UserNotFoundException();
        }

        return $this->entityToTransfer($entity);
    }

    /**
     * @param int $id
     *
     * @return TransferUser
     * @throws UserNotFoundException
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
     * @param TransferUser $user
     *
     * @return TransferUser
     */
    public function setCurrentUser(TransferUser $user)
    {
        $session = $this->locator->application()->pluginSession();
        $key = sprintf('%s:currentUser', self::USER_BUNDLE_SESSION_KEY);

        return $session->set($key, serialize($user));
    }

    /**
     * @return bool
     */
    public function hasCurrentUser()
    {
        try {
            $this->getCurrentUser();
            return true;
        } catch (UserNotFoundException $e) {
            return false;
        }
    }

    /**
     * @param TransferUser $user
     *
     * @return bool
     */
    public function isSystemUser(TransferUser $user)
    {
        $systemUser = $this->settings->getSystemUsers();

        foreach ($systemUser as $username) {
            if ($username === $user->getUsername()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return UserCollection
     */
    public function getSystemUsers()
    {
        $systemUser = $this->settings->getSystemUsers();
        $collection = $this->locator->user()->transferUserCollection();

        foreach ($systemUser as $username) {
            $transferUser = new \Generated\Shared\Transfer\UserUserTransfer();

            // TODO why setting the id? why is everything the username?
            $transferUser->setIdUserUser(0);

            $transferUser->setFirstName($username)
                ->setLastName($username)
                ->setUsername($username)
                ->setPassword($username)
            ;

            $collection->add($transferUser);
        }

        return $collection;
    }

    /**
     * @return TransferUser
     * @throws UserNotFoundException
     */
    public function getCurrentUser()
    {
        $session = $this->locator->application()->pluginSession();
        $key = sprintf('%s:currentUser', self::USER_BUNDLE_SESSION_KEY);
        $user = unserialize($session->get($key));

        if ($user === false) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @param SpyUserUser $entity
     *
     * @return TransferUser
     */
    protected function entityToTransfer(SpyUserUser $entity)
    {
        $transfer = new \Generated\Shared\Transfer\UserUserTransfer();
        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
    }
}
