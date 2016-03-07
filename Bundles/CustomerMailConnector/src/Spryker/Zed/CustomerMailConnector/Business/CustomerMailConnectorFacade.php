<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerMailConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomerMailConnector\Business\CustomerMailConnectorBusinessFactory getFactory()
 */
class CustomerMailConnectorFacade extends AbstractFacade implements CustomerMailConnectorFacadeInterface
{

    /**
     * @api
     *
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
     * @api
     *
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
     * @api
     *
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
