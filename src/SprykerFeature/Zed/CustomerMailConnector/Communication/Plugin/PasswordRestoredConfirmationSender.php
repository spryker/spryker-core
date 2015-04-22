<?php

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Mail\Transfer\MailTransfer;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface;

class PasswordRestoredConfirmationSender extends AbstractPlugin implements PasswordRestoredConfirmationSenderPluginInterface
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
