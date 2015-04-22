<?php

namespace SprykerFeature\Shared\User\Transfer;

class User extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idUserUser = null;
    protected $username = null;
    protected $password = null;
    protected $firstName = null;
    protected $status = null;

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        $this->addModifiedProperty('first_name');
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     *
     * @return User
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
        $this->addModifiedProperty('last_name');
        return $this;
    }
    protected $last_name = null;

    /**
     * @return int
     */
    public function getIdUserUser()
    {
        return $this->idUserUser;
    }

    /**
     * @param int $idUserUser
     *
     * @return User
     */
    public function setIdUserUser($idUserUser)
    {
        $this->idUserUser = $idUserUser;
        $this->addModifiedProperty('id_user_user');
        return $this;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        $this->addModifiedProperty('username');
        return $this;
    }
    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        $this->addModifiedProperty('password');
        return $this;
    }
    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $status
     *
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;
        $this->addModifiedProperty('status');
        return $this;
    }
    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
