<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerMailConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CustomerMailConnectorDependencyContainer getDependencyContainer()
 */
class CustomerMailConnectorFacade extends AbstractFacade
{

    /**
     * @param string $email
     * @param string $token
     *
     * @return bool
     */
    public function sendRegistrationToken($email, $token)
    {
        return $this->getDependencyContainer()
            ->createRegistrationTokenSender()
            ->send($email, $token);
    }

    /**
     * @param string $email
     * @param string $token
     *
     * @return bool
     */
    public function sendPasswordRestoreToken($email, $token)
    {
        return $this->getDependencyContainer()
            ->createPasswordRestoreTokenSender()
            ->send($email, $token);
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function sendPasswordRestoredConfirmation($email)
    {
        return $this->getDependencyContainer()
            ->createPasswordRestoredConfirmationSender()
            ->send($email);
    }

}
