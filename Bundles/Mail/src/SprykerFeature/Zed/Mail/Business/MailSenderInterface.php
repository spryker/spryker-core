<?php


namespace SprykerFeature\Zed\Mail\Business;


use Generated\Shared\Transfer\MailMailTransfer;

interface MailSenderInterface
{
    /**
     * @param MailMailTransfer $mailTransfer
     *
     * @return array
     */
    public function sendMail(MailMailTransfer $mailTransfer);
}
