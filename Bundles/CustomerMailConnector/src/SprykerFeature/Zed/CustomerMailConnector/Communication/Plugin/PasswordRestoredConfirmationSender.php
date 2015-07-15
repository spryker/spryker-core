<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface;
use SprykerFeature\Zed\CustomerMailConnector\Communication\CustomerMailConnectorDependencyContainer;

/**
 * @method CustomerMailConnectorDependencyContainer getDependencyContainer()
 */
class PasswordRestoredConfirmationSender extends AbstractSender implements PasswordRestoredConfirmationSenderPluginInterface
{

    const SUBJECT = 'password.restored.sender.subject';
    const TEMPLATE = 'password.change.confirmation';

    /**
     * @param string $email
     *
     * @return bool
     */
    public function send($email)
    {
        $mailTransfer = new MailTransfer();
        $mailRecipientTransfer = new MailRecipientTransfer();
        $mailRecipientTransfer->setEmail($email);

        $mailTransfer->addRecipient($mailRecipientTransfer);
        $mailTransfer->setSubject(self::SUBJECT);
        $mailTransfer->setTemplateName(self::TEMPLATE);
        $mailTransfer->setMerge(true);
        $mailTransfer->setMergeLanguage('handlebars');

        $result = $this->getDependencyContainer()
            ->createMailFacade()
            ->sendMail($mailTransfer);

        return $this->isMailSent($result);
    }

}
