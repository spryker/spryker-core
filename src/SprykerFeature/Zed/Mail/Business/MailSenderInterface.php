<?php


namespace SprykerFeature\Zed\Mail\Business;


use SprykerFeature\Shared\Mail\Transfer\MailTransfer;

interface MailSenderInterface
{
    /**
     * @param MailTransfer $mailTransfer
     *
     * @return array
     */
    public function sendMail(MailTransfer $mailTransfer);
}
