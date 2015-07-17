<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Customer\Model;

use Silex\Application;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use LogicException;

class Customer
{

    /** @var Application */
    protected $application;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        $token = $this->getToken();
        if ($token && !($token instanceof AnonymousToken)) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        $token = $this->getToken();
        if (!$token) {
            throw new LogicException('No security token found.');
        }

        $user = $token->getUser();
        if (is_string($user)) {
            return $user;
        }

        return $user->getUsername();
    }

    /**
     * @return TokenInterface
     */
    protected function getToken()
    {
        $security = $this->application['security'];
        if (!$security) {
            return;
        }
        $token = $security->getToken();
        if (!$token) {
            return;
        }

        return $token;
    }

}
