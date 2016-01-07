<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Dependency\Facade;

use Spryker\Zed\Mail\Business\MailFacade;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\SendMailResponsesTransfer;

class NewsletterToMailBridge implements NewsletterToMailInterface
{

    /**
     * @var MailFacade
     */
    protected $mailFacade;

    /**
     * @param MailFacade $mailFacade
     */
    public function __construct($mailFacade)
    {
        $this->mailFacade = $mailFacade;
    }

    /**
     * @param MailTransfer $mailTransfer
     *
     * @return SendMailResponsesTransfer
     */
    public function sendMail(MailTransfer $mailTransfer)
    {
        return $this->mailFacade->sendMail($mailTransfer);
    }

    /**
     * @param SendMailResponsesTransfer $mailResponses
     *
     * @return bool
     */
    public function isMailSent(SendMailResponsesTransfer $mailResponses)
    {
        return $this->mailFacade->isMailSent($mailResponses);
    }

}
