<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Generated\Shared\Transfer\MailTransferTransfer;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface;

class PasswordRestoredConfirmationSender extends AbstractSender implements PasswordRestoredConfirmationSenderPluginInterface
{
    const SUBJECT = 'password.restored.sender.subject';
    const TEMPLATE = 'password.restored';

    /**
     * @param string $email
     *
     * @return bool
     */
    public function send($email)
    {
        $mailTransfer = $this->getMailTransfer();

        $mailTransfer->addRecipient($email);
        $mailTransfer->setSubject(self::SUBJECT);
        $mailTransfer->setTemplateName(self::TEMPLATE);

        $result = $this->getDependencyContainer()
            ->createMailFacade()
            ->sendMail($mailTransfer);

        return $this->isMailSent($result);
    }
}
