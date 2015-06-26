<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoreTokenSenderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin
use Generated\Shared\Transfer\MailMail as MailTransferTransfer;

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
