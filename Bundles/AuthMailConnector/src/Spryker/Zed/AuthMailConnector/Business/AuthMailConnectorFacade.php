<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthMailConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AuthMailConnector\Business\AuthMailConnectorBusinessFactory getFactory()
 */
class AuthMailConnectorFacade extends AbstractFacade implements AuthMailConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $email
     * @param string $token
     *
     * @return void
     */
    public function sendResetPasswordMail(string $email, string $token): void
    {
        $this->getFactory()->createMailSender()->sendResetPasswordMail($email, $token);
    }
}
