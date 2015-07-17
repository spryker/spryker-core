<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service\Session;

use Generated\Shared\Customer\CustomerInterface;
use SprykerFeature\Client\Session\Service\SessionClientInterface;

class CustomerSession implements CustomerSessionInterface
{

    const SESSION_KEY_PREFIX = 'userdata:';

    /**
     * @var SessionClientInterface
     */
    private $sessionClient;

    /**
     * @param SessionClientInterface $sessionClient
     */
    public function __construct(SessionClientInterface $sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @param CustomerInterface $customerTransfer
     */
    public function logout(CustomerInterface $customerTransfer)
    {
        $this->sessionClient->remove(
            $this->getKey($customerTransfer->getEmail())
        );
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function get(CustomerInterface $customerTransfer)
    {
        return $this->sessionClient->get(
            $this->getKey($customerTransfer->getEmail())
        );
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function set(CustomerInterface $customerTransfer)
    {
        $this->sessionClient->set(
            $this->getKey($customerTransfer->getEmail()),
            $customerTransfer
        );

        return $customerTransfer;
    }

    /**
     * @param string $username
     *
     * @return string
     */
    private function getKey($username)
    {
        return self::SESSION_KEY_PREFIX . $username;
    }

}
