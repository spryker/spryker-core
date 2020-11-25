<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordResetMail\Communication\Plugin\UserPasswordReset;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\UserPasswordResetRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserPasswordResetExtension\Dependency\Plugin\UserPasswordResetRequestHandlerPluginInterface;
use Spryker\Zed\UserPasswordResetMail\Communication\Plugin\Mail\UserPasswordResetMailTypePlugin;

/**
 * @method \Spryker\Zed\UserPasswordResetMail\UserPasswordResetMailConfig getConfig()
 * @method \Spryker\Zed\UserPasswordResetMail\Communication\UserPasswordResetMailCommunicationFactory getFactory()
 */
class MailUserPasswordResetRequestHandlerPlugin extends AbstractPlugin implements UserPasswordResetRequestHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sends user reset password email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer
     *
     * @return void
     */
    public function handleUserPasswordResetRequest(UserPasswordResetRequestTransfer $userPasswordResetRequestTransfer): void
    {
        $this->getFactory()
            ->getMailFacade()
            ->handleMail(
                (new MailTransfer())
                ->fromArray($userPasswordResetRequestTransfer->toArray(), true)
                ->setType(UserPasswordResetMailTypePlugin::MAIL_TYPE)
            );
    }
}
