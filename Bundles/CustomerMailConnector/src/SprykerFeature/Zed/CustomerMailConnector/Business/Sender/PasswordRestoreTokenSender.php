<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Business\Sender;

use Generated\Shared\Transfer\MailTransfer;

class PasswordRestoreTokenSender extends AbstractSender
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

        $mailTransfer->setTemplateName($this->config->getPasswordRestoreToken());

        $this->addMailRecipient($mailTransfer, $email);
        $this->setMailTransferFrom($mailTransfer);
        $this->setMailTransferSubject($mailTransfer);
        $this->setMailMergeData($mailTransfer, $this->getMailGlobalMergeVars($token));

        $result = $this->mailFacade->sendMail($mailTransfer);

        return $this->isMailSent($result);
    }

    /**
     * @param MailTransfer $mailTransfer
     *
     * @return void
     */
    protected function setMailTransferSubject(MailTransfer $mailTransfer)
    {
        $subject = $this->config->getPasswordRestoreSubject();
        if ($subject !== null) {
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
            'reset_password_token_url' => $token,
        ];

        return $globalMergeVars;
    }

}
