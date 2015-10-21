<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Business\Sender;

use Generated\Shared\Transfer\MailTransfer;

class PasswordRestoredConfirmationSender extends AbstractSender
{

    /**
     * @param string $email
     *
     * @return bool
     */
    public function send($email)
    {
        $mailTransfer = $this->createMailTransfer();

        $mailTransfer->setTemplateName($this->config->getPasswordRestoredConfirmationToken());

        $this->addMailRecipient($mailTransfer, $email);
        $this->setMailTransferFrom($mailTransfer);
        $this->setMailTransferSubject($mailTransfer);
        $this->setMailMergeData($mailTransfer);

        $result = $this->mailFacade->sendMail($mailTransfer);

        return $this->isMailSent($result);
    }

    /**
     * @param MailTransfer $mailTransfer
     */
    protected function setMailTransferSubject(MailTransfer $mailTransfer)
    {
        $subject = $this->config->getPasswordRestoredConfirmationSubject();
        if (null !== $subject) {
            $mailTransfer->setSubject($this->translate($subject));
        }
    }

}
