<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Business\Model;

use Generated\Shared\Transfer\CollectionTransfer;
use SprykerFeature\Zed\Library\Copy;
use Propel\Runtime\Collection\ObjectCollection;
use Generated\Shared\Transfer\UserTransfer;
use SprykerFeature\Zed\User\Persistence\Propel\Map\SpyUserUserTableMap;
use SprykerFeature\Zed\User\UserConfig;
use SprykerFeature\Zed\User\Persistence\Propel\SpyUserUser;
use SprykerFeature\Zed\User\Persistence\UserQueryContainer;
use SprykerFeature\Zed\User\Business\Exception\UserNotFoundException;
use SprykerFeature\Zed\User\Business\Exception\UsernameExistsException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class User implements UserInterface
{

    const USER_BUNDLE_SESSION_KEY = 'user';

    /**
     * @var UserQueryContainer
     */
    protected $queryContainer;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var UserConfig
     */
    protected $settings;

    /**
     * @param UserQueryContainer $queryContainer
     * @param SessionInterface $session
     * @param UserConfig $settings
     */
    public function __construct(
        UserQueryContainer $queryContainer,
        SessionInterface $session,
        UserConfig $settings
    ) {
        $this->queryContainer = $queryContainer;
        $this->session = $session;
        $this->settings = $settings;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string $password
     *
     * @throws UsernameExistsException
     *
     * @return UserTransfer
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
     * @param UserTransfer $user
     *
     * @return UserTransfer
     */
    public function save(UserTransfer $user)
    {
        if ($user->getIdUserUser() !== null) {
            $entity = $this->getEntityUserById($user->getIdUserUser());
        } else {
            $entity = new SpyUserUser();
        }

        $entity->setFirstName($user->getFirstName());
        $entity->setLastName($user->getLastName());
        $entity->setUsername($user->getUsername());

        if (!is_null($user->getStatus())) {
            $entity->setStatus($user->getStatus());
        }

        $password = $user->getPassword();
        if (!empty($password) && true === $this->isRawPassword($user->getPassword())) {
            $entity->setPassword($this->encryptPassword($user->getPassword()));
        }

        $entity->save();
        $transfer = $this->entityToTransfer($entity);

        return $transfer;
    }

    /**
     * @param int $idUser
     *
     * @return UserTransfer
     */
    public function removeUser($idUser)
    {
        $user = $this->getUserById($idUser);
        $user->setStatus('deleted');

        return $this->save($user);
    }

    /**
     * @throws UserNotFoundException
     *
     * @return UserTransfer
     */
    public function getUsers()
    {
        $results = $this->queryContainer->queryUsers()->find();

        if (false === ($results instanceof ObjectCollection)) {
            throw new UserNotFoundException();
        }

        $collection = new TransferArrayObject();

        foreach ($results as $result) {
            $transfer = new UserTransfer();
            $collection->add(Copy::entityToTransfer($transfer, $result));
        }

        return $collection;
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
     * @throws UserNotFoundException
     *
     * @return UserTransfer
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
     * @throws UserNotFoundException
     *
     * @return UserTransfer
     */
    public function getUserById($id)
    {
        $entity = $this->queryContainer
            ->queryUserById($id)
            ->filterByStatus(SpyUserUserTableMap::COL_STATUS_ACTIVE)
            ->findOne()
        ;

        if ($entity === null) {
            throw new UserNotFoundException();
        }

        return $this->entityToTransfer($entity);
    }

    /**
     * @param int $id
     *
     * @throws UserNotFoundException
     *
     * @return UserTransfer
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
     * @param UserTransfer $user
     *
     * @return mixed
     */
    public function setCurrentUser(UserTransfer $user)
    {
        $key = sprintf('%s:currentUser', self::USER_BUNDLE_SESSION_KEY);

        return $this->session->set($key, serialize($user));
    }

    /**
     * @return bool
     */
    public function hasCurrentUser()
    {
        $key = sprintf('%s:currentUser', self::USER_BUNDLE_SESSION_KEY);
        $user = unserialize($this->session->get($key));

        return $user !== false;
    }

    /**
     * @param UserTransfer $user
     *
     * @return bool
     */
    public function isSystemUser(UserTransfer $user)
    {
        $systemUsers = $this->settings->getSystemUsers();

        return in_array($user->getUsername(), $systemUsers);
    }

    /**
     * @return CollectionTransfer
     */
    public function getSystemUsers()
    {
        $systemUser = $this->settings->getSystemUsers();
        $collection = new CollectionTransfer();

        foreach ($systemUser as $username) {
            $transferUser = new UserTransfer();

            // TODO why setting the id? why is everything the username?
            $transferUser->setIdUserUser(0);

            $transferUser->setFirstName($username)
                ->setLastName($username)
                ->setUsername($username)
                ->setPassword($username)
            ;

            $collection->addUser($transferUser);
        }

        return $collection;
    }

    /**
     * @throws UserNotFoundException
     *
     * @return UserTransfer
     */
    public function getCurrentUser()
    {
        $key = sprintf('%s:currentUser', self::USER_BUNDLE_SESSION_KEY);
        $user = unserialize($this->session->get($key));

        if ($user === false) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @param SpyUserUser $entity
     *
     * @return UserTransfer
     */
    protected function entityToTransfer(SpyUserUser $entity)
    {
        $transfer = new UserTransfer();
        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
    }

}
