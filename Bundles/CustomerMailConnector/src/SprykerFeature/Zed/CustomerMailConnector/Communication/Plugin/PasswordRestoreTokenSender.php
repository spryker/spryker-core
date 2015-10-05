<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use SprykerFeature\Shared\Mail\MailConfig;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoreTokenSenderPluginInterface;
use SprykerFeature\Zed\CustomerMailConnector\Communication\CustomerMailConnectorDependencyContainer;

/**
 * @method CustomerMailConnectorDependencyContainer getDependencyContainer()
 */
class PasswordRestoreTokenSender extends AbstractSender implements PasswordRestoreTokenSenderPluginInterface
{

    /**
     * @param string $email
     * @param string $token
     *
     * @return bool
     */
    public function send($email, $token)
    {
        $config = $this->getDependencyContainer()->getConfig();

        $mailTransfer = new MailTransfer();
        $mailRecipientTransfer = new MailRecipientTransfer();
        $mailRecipientTransfer->setEmail($email);

        $mailTransfer->addRecipient($mailRecipientTransfer);
        $mailTransfer->setFromName($config->getFromEmailName());
        $mailTransfer->setFromEmail($config->getFromEmailAddress());
        $mailTransfer->setSubject($config->getPasswordRestoreSubject());
        $mailTransfer->setTemplateName($config->getPasswordRestoreToken());
        $mailTransfer->setMerge(true);
        $mailTransfer->setMergeLanguage(MailConfig::MERGE_LANGUAGE_HANDLEBARS);
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
