<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use Generated\Shared\Transfer\MailRecipientTransfer;
use SprykerFeature\Zed\Customer\Dependency\Plugin\RegistrationTokenSenderPluginInterface;
use Generated\Shared\Transfer\MailTransfer;
use SprykerFeature\Zed\CustomerMailConnector\Communication\CustomerMailConnectorDependencyContainer;

/**
 * @method CustomerMailConnectorDependencyContainer getDependencyContainer()
 */
class RegistrationTokenSender extends AbstractSender implements RegistrationTokenSenderPluginInterface
{

    const SUBJECT = 'registration.token.sender.subject';
    const TEMPLATE = 'registration.token';

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
                'registration_token_url' => $token,
        ];
        $mailTransfer->setGlobalMergeVars($globalMergeVars);

        $result = $this->getDependencyContainer()
            ->createMailFacade()
            ->sendMail($mailTransfer);

        return $this->isMailSent($result);
    }

}
