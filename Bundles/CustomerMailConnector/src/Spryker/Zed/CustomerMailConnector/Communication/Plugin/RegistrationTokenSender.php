<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerMailConnector\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Customer\Dependency\Plugin\RegistrationTokenSenderPluginInterface;
use Spryker\Zed\CustomerMailConnector\Business\CustomerMailConnectorFacade;
use Spryker\Zed\CustomerMailConnector\Communication\CustomerMailConnectorCommunicationFactory;

/**
 * @method CustomerMailConnectorFacade getFacade()
 * @method CustomerMailConnectorCommunicationFactory getFactory()
 */
class RegistrationTokenSender extends AbstractPlugin implements RegistrationTokenSenderPluginInterface
{

    /**
     * @param string $email
     * @param string $token
     *
     * @return bool
     */
    public function send($email, $token)
    {
        return $this->getFacade()->sendRegistrationToken($email, $token);
    }

}
