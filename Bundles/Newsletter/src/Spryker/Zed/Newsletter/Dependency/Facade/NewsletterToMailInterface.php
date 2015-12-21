<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Dependency\Facade;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\SendMailResponsesTransfer;

interface NewsletterToMailInterface
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
