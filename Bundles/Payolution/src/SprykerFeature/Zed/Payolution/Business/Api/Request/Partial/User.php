<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request\Partial;

use SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequest;

class User extends AbstractRequest
{
    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $pwd;

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * @param string $password
     */
    public function setPwd($password)
    {
        $this->pwd = $password;
    }

    /**
     * @return array
     */
    protected function getXmlAttributeProperties()
    {
        return ['login', 'pwd'];
    }

}
