<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerMailConnector\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Customer\Dependency\Plugin\RegistrationTokenSenderPluginInterface;

/**
 * @method \Spryker\Zed\CustomerMailConnector\Business\CustomerMailConnectorFacade getFacade()
 * @method \Spryker\Zed\CustomerMailConnector\Communication\CustomerMailConnectorCommunicationFactory getFactory()
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
