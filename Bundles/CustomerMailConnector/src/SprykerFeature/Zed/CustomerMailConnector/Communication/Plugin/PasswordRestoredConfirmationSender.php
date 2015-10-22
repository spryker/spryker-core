<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface;
use SprykerFeature\Zed\CustomerMailConnector\Business\CustomerMailConnectorFacade;

/**
 * @method CustomerMailConnectorFacade getFacade()
 */
class PasswordRestoredConfirmationSender extends AbstractPlugin implements PasswordRestoredConfirmationSenderPluginInterface
{

    /**
     * @param string $email
     *
     * @return bool
     */
    public function send($email)
    {
        return $this->getFacade()->sendPasswordRestoredConfirmation($email);
    }

}
