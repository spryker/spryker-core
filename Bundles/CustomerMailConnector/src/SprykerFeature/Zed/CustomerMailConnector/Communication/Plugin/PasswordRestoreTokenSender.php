<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoreTokenSenderPluginInterface;
use SprykerFeature\Zed\CustomerMailConnector\Communication\CustomerMailConnectorDependencyContainer;

/**
 * @method CustomerMailConnectorDependencyContainer getDependencyContainer()
 */
class PasswordRestoreTokenSender extends AbstractSender implements PasswordRestoreTokenSenderPluginInterface
{

    const SUBJECT = 'password.restore.sender.subject';
    const TEMPLATE = 'password.restore';

    /**
     * @param string $email
     * @param string $token
     *
     * @return bool
     */
    public function send($email, $token)
    {
        $mailTransfer = new MailTransfer();
        $mailRecipientTransfer = new MailRecipientTransfer();
        $mailRecipientTransfer->setEmail($email);

        $mailTransfer->addRecipient($mailRecipientTransfer);
        $mailTransfer->setSubject(self::SUBJECT);
        $mailTransfer->setTemplateName(self::TEMPLATE);
        $mailTransfer->setMerge(true);
        $mailTransfer->setMergeLanguage('handlebars');
        $globalMergeVars = [
            'reset_password_token_url' => $token,
        ];
        $mailTransfer->setGlobalMergeVars($globalMergeVars);

        $result = $this->getDependencyContainer()
            ->createMailFacade()
            ->sendMail($mailTransfer);

        return $this->isMailSent($result);
    }

}
