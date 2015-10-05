<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use SprykerFeature\Shared\Mail\MailConfig;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface;
use SprykerFeature\Zed\CustomerMailConnector\Communication\CustomerMailConnectorDependencyContainer;

/**
 * @method CustomerMailConnectorDependencyContainer getDependencyContainer()
 */
class PasswordRestoredConfirmationSender extends AbstractSender implements PasswordRestoredConfirmationSenderPluginInterface
{

    /**
     * @param string $email
     *
     * @return bool
     */
    public function send($email)
    {
        $config = $this->getDependencyContainer()->getConfig();

        $mailTransfer = new MailTransfer();
        $mailRecipientTransfer = new MailRecipientTransfer();
        $mailRecipientTransfer->setEmail($email);

        $mailTransfer->addRecipient($mailRecipientTransfer);
        $mailTransfer->setFromName($config->getFromEmailName());
        $mailTransfer->setFromEmail($config->getFromEmailAddress());
        $mailTransfer->setSubject($config->getPasswordRestoredConfirmationSubject());
        $mailTransfer->setTemplateName($config->getPasswordRestoredConfirmationToken());
        $mailTransfer->setMerge(true);
        $mailTransfer->setMergeLanguage(MailConfig::MERGE_LANGUAGE_HANDLEBARS);

        $result = $this->getDependencyContainer()
            ->createMailFacade()
            ->sendMail($mailTransfer);

        return $this->isMailSent($result);
    }

}
