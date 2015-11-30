<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Mail\Business;

use Generated\Shared\Transfer\SendMailResponsesTransfer;
use Generated\Shared\Transfer\MailTransfer;

interface MailSenderInterface
{

    /**
     * @param MailTransfer $mailTransfer
     *
     * @return SendMailResponsesTransfer
     */
    public function sendMail(MailTransfer $mailTransfer);

    /**
     * @param SendMailResponsesTransfer $mailResponses
     *
     * @return bool
     */
    public function isMailSent(SendMailResponsesTransfer $mailResponses);

}
