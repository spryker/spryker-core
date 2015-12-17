<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerMailConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CustomerMailConnectorBusinessFactory getFactory()
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
        return $this->getFactory()
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
        return $this->getFactory()
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
        return $this->getFactory()
            ->createPasswordRestoredConfirmationSender()
            ->send($email);
    }

}
