<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Mail\Business;

use Generated\Shared\Mail\SendMailResponsesInterface;
use Generated\Shared\Transfer\MailTransfer;

interface MailSenderInterface
{

    /**
     * @param MailTransfer $mailTransfer
     *
     * @return SendMailResponsesInterface
     */
    public function sendMail(MailTransfer $mailTransfer);

}
