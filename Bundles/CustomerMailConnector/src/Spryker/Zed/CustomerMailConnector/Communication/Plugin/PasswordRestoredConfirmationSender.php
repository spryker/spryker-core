<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerMailConnector\Communication\Plugin;

use Spryker\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

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
