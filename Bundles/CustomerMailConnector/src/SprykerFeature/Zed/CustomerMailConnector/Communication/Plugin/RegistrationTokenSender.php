<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Generated\Shared\Transfer\MailTransferTransfer;
use SprykerFeature\Zed\Customer\Dependency\Plugin\RegistrationTokenSenderPluginInterface;

class RegistrationTokenSender extends AbstractPlugin implements RegistrationTokenSenderPluginInterface
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
