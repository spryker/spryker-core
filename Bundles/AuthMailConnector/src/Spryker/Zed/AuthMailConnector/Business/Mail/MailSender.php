<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthMailConnector\Business\Mail;

use Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailInterface;

class MailSender implements MailSenderInterface
{
    /**
     * @var \Spryker\Zed\AuthMailConnector\Business\Mail\MailBuilderInterface
     */
    protected $mailBuilder;

    /**
     * @var \Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailInterface
     */
    protected $mailFacade;

    /**
     * @param \Spryker\Zed\AuthMailConnector\Business\Mail\MailBuilderInterface $mailBuilder
     * @param \Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailInterface $mailFacade
     */
    public function __construct(
        MailBuilderInterface $mailBuilder,
        AuthMailConnectorToMailInterface $mailFacade
    ) {
        $this->mailBuilder = $mailBuilder;
        $this->mailFacade = $mailFacade;
    }

    /**
     * @param string $email
     * @param string $token
     *
     * @return void
     */
    public function sendResetPasswordMail(string $email, string $token): void
    {
        $resetPasswordMailTransfer = $this->mailBuilder->buildResetPasswordMailTransfer($email, $token);

        $this->mailFacade->handleMail($resetPasswordMailTransfer);
    }
}
