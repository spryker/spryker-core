<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\Session;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Session\SessionClientInterface;

class CustomerSession implements CustomerSessionInterface
{
    const SESSION_KEY = 'customer data';

    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    private $sessionClient;

    /**
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     */
    public function __construct(SessionClientInterface $sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @return void
     */
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
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer()
    {
        return $this->sessionClient->get(self::SESSION_KEY);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function setCustomer(CustomerTransfer $customerTransfer)
    {
        $this->sessionClient->set(
            self::SESSION_KEY,
            $customerTransfer
        );

        return $customerTransfer;
    }
}
