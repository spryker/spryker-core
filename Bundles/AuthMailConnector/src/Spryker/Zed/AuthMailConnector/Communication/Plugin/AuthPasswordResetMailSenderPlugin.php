<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\AuthMailConnector\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Auth\Dependency\Plugin\AuthPasswordResetSenderInterface;
use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;

/**
 * @method \Spryker\Zed\AuthMailConnector\Communication\AuthMailConnectorCommunicationFactory getFactory()
 */
class AuthPasswordResetMailSenderPlugin extends AbstractPlugin implements AuthPasswordResetSenderInterface
{

    const SUBJECT = 'Password reset request';
    const TEMPLATE = 'Auth.password.reset';

    /**
     * @param string $email
     * @param string $token
     *
     * @return mixed
     */
    public function send($email, $token)
    {
        $mailTransfer = new MailTransfer();
        $mailRecipientTransfer = new MailRecipientTransfer();
        $mailRecipientTransfer->setEmail($email);

        $mailTransfer->addRecipient($mailRecipientTransfer);
        $mailTransfer->setSubject(static::SUBJECT);
        $mailTransfer->setTemplateName(static::TEMPLATE);
        $mailTransfer->setMerge(true);
        $mailTransfer->setMergeLanguage('handlebars');
        $mailTransfer->setGlobalMergeVars([
            'reset_password_token' => $token,
        ]);

        $this->getFactory()
            ->getMailFacade()
            ->sendMail($mailTransfer);
    }

}
