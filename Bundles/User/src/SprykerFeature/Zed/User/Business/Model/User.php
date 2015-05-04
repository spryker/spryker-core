<?php

namespace SprykerFeature\Zed\User\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Library\Copy;
use Propel\Runtime\Collection\ObjectCollection;
use Generated\Shared\Transfer\UserUserTransfer;
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
     * @return UserUserTransfer
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
     * @param UserUserTransfer $data
     *
     * @return UserUserTransfer
     * @throws UserNotFoundException
     */
    public function save(UserUserTransfer $data)
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
     * @return UserUserTransfer
     */
    public function removeUser($idUser)
    {
        $user = $this->getUserById($idUser);
        $user->setStatus('deleted');

        return $this->save($user);
    }

    /**
     * @return UserUserTransfer
     * @throws UserNotFoundException
     */
    public function getUsers()
    {
        $users = $this->queryContainer->queryUsers()->find();

        if (false === ($users instanceof ObjectCollection)) {
            throw new UserNotFoundException();
        }

        $userCollection = new \Generated\Shared\Transfer\UserUserTransfer();

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
     * @return UserUserTransfer
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
     * @return UserUserTransfer
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
     * @param UserUserTransfer $user
     *
     * @return UserUserTransfer
     */
    public function setCurrentUser(UserUserTransfer $user)
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
            $test = $this->getCurrentUser();
            return true;
        } catch (UserNotFoundException $e) {
            return false;
        }
    }

    /**
     * @param UserUserTransfer $user
     *
     * @return bool
     */
    public function isSystemUser(UserUserTransfer $user)
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
     * @return UserUserTransfer
     */
    public function getSystemUsers()
    {
        $systemUser = $this->settings->getSystemUsers();
        $collection = new \Generated\Shared\Transfer\UserUserTransfer();

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
     * @return UserUserTransfer
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
     * @return UserUserTransfer
     */
    protected function entityToTransfer(SpyUserUser $entity)
    {
        $transfer = new \Generated\Shared\Transfer\UserUserTransfer();
        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
    }
}
