<?php


namespace SprykerFeature\Zed\Mail\Business;


use Generated\Shared\Transfer\MailMailTransferTransfer;

interface MailSenderInterface
{
    /**
     * @param MailTransfer $mailTransfer
     *
     * @return array
     */
    public function sendMail(MailTransfer $mailTransfer);
}
