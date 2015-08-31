<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\AuthMailConnector\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Auth\Dependency\Plugin\AuthPasswordResetSenderInterface;
use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use SprykerFeature\Zed\AuthMailConnector\Communication\AuthMailConnectorDependencyContainer;

/**
 * @method AuthMailConnectorDependencyContainer getDependencyContainer()
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
            'reset_password_token' => $token
        ]);

        $this->getDependencyContainer()
            ->creatMailFacade()
            ->sendMail($mailTransfer);
    }
}
