<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerMailConnector\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Customer\Dependency\Plugin\PasswordRestoreTokenSenderPluginInterface;
use Spryker\Zed\CustomerMailConnector\Business\CustomerMailConnectorFacade;

/**
 * @method CustomerMailConnectorFacade getFacade()
 */
class PasswordRestoreTokenSender extends AbstractPlugin implements PasswordRestoreTokenSenderPluginInterface
{

    /**
     * @param string $email
     * @param string $token
     *
     * @return bool
     */
    public function send($email, $token)
    {
        return $this->getFacade()->sendPasswordRestoreToken($email, $token);
    }

}
