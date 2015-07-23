<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service\Session;

use Generated\Shared\Customer\CustomerInterface;
use SprykerFeature\Client\Session\Service\SessionClientInterface;

class CustomerSession implements CustomerSessionInterface
{

    const SESSION_KEY = 'customer data';

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

    public function logout()
    {
        $this->sessionClient->remove(self::SESSION_KEY);
    }

    /**
     * @return bool
     */
    public function hasCustomer()
    {
        return $this->sessionClient->has(self::SESSION_KEY);
    }

    /**
     * @return CustomerInterface
     */
    public function getCustomer()
    {
        return $this->sessionClient->get(self::SESSION_KEY);
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function setCustomer(CustomerInterface $customerTransfer)
    {
        $this->sessionClient->set(
            self::SESSION_KEY,
            $customerTransfer
        );

        return $customerTransfer;
    }

}
