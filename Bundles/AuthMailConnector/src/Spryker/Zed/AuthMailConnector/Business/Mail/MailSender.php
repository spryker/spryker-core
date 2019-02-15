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
     * @var \Spryker\Zed\AuthMailConnector\Business\Mail\MailTransferGeneratorInterface
     */
    protected $mailTransferGenerator;

    /**
     * @var \Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailInterface
     */
    protected $mailFacade;

    /**
     * @param \Spryker\Zed\AuthMailConnector\Business\Mail\MailTransferGeneratorInterface $mailTransferGenerator
     * @param \Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailInterface $mailFacade
     */
    public function __construct(
        MailTransferGeneratorInterface $mailTransferGenerator,
        AuthMailConnectorToMailInterface $mailFacade
    ) {
        $this->mailTransferGenerator = $mailTransferGenerator;
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
        $resetPasswordMailTransfer = $this->mailTransferGenerator->createResetPasswordMailTransfer($email, $token);

        $this->mailFacade->handleMail($resetPasswordMailTransfer);
    }
}
