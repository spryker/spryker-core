<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerMailConnector\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface;

/**
 * @method \Spryker\Zed\CustomerMailConnector\Business\CustomerMailConnectorFacade getFacade()
 * @method \Spryker\Zed\CustomerMailConnector\Communication\CustomerMailConnectorCommunicationFactory getFactory()
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
