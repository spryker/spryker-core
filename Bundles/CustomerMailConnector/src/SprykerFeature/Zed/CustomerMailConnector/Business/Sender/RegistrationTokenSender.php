<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Business\Sender;

use Generated\Shared\Transfer\MailTransfer;

class RegistrationTokenSender extends AbstractSender
{

    /**
     * @param string $email
     * @param string $token
     *
     * @return bool
     */
    public function send($email, $token)
    {
        $mailTransfer = $this->createMailTransfer();

        $mailTransfer->setTemplateName($this->config->getRegistrationToken());

        $this->addMailRecipient($mailTransfer, $email);
        $this->setMailTransferFrom($mailTransfer);
        $this->setMailTransferSubject($mailTransfer);
        $this->setMailMergeData($mailTransfer, $this->getMailGlobalMergeVars($token));

        $result = $this->mailFacade->sendMail($mailTransfer);

        return $this->isMailSent($result);
    }

    /**
     * @param MailTransfer $mailTransfer
     */
    protected function setMailTransferSubject(MailTransfer $mailTransfer)
    {
        $subject = $this->config->getRegistrationSubject();
        if (null !== $subject) {
            $mailTransfer->setSubject($this->translate($subject));
        }
    }

    /**
     * @param string $token
     *
     * @return array
     */
    protected function getMailGlobalMergeVars($token)
    {
        $globalMergeVars = [
            'registration_token_url' => $token,
        ];

        return $globalMergeVars;
    }

}
