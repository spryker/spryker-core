<?php

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoreTokenSenderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Generated\Shared\Transfer\MailMail as MailTransferTransfer;

class PasswordRestoreTokenSender extends AbstractPlugin implements PasswordRestoreTokenSenderPluginInterface
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

    /**
     * @return MailTransfer
     */
    protected function getMailTransfer()
    {
        return $this->getDependencyContainer()->createMailTransfer();
    }

    /**
     * @param array $results
     *
     * @return bool
     */
    protected function isMailSent(array $results)
    {
        foreach ($results as $result) {
            if (!isset($result['status'])) {
                return false;
            }
            if ($result['status'] !== 'sent') {
                return false;
            }
        }

        return true;
    }
}
