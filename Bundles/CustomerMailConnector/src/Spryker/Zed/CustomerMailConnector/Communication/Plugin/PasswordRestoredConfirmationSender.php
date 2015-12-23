<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerMailConnector\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface;
use Spryker\Zed\CustomerMailConnector\Business\CustomerMailConnectorFacade;
use Spryker\Zed\CustomerMailConnector\Communication\CustomerMailConnectorCommunicationFactory;

/**
 * @method CustomerMailConnectorFacade getFacade()
 * @method CustomerMailConnectorCommunicationFactory getFactory()
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
