<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoreTokenSenderPluginInterface;
use SprykerFeature\Zed\CustomerMailConnector\Business\CustomerMailConnectorFacade;

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
