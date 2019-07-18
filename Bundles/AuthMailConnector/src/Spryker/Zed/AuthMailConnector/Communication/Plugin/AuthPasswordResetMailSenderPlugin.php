<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthMailConnector\Communication\Plugin;

use Spryker\Zed\Auth\Dependency\Plugin\AuthPasswordResetSenderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AuthMailConnector\Business\AuthMailConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\AuthMailConnector\Communication\AuthMailConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig getConfig()
 */
class AuthPasswordResetMailSenderPlugin extends AbstractPlugin implements AuthPasswordResetSenderInterface
{
    /**
     * @deprecated
     */
    public const SUBJECT = 'Password reset request';

    /**
     * {@inheritdoc}
     * - Generates MailTransfer for reset password functionality.
     * - Uses `MailFacade::handleMail()` to handle generated MailTransfer.
     *
     * @api
     *
     * @param string $email
     * @param string $token
     *
     * @return void
     */
    public function send($email, $token)
    {
        $this->getFacade()->sendResetPasswordMail($email, $token);
    }
}
