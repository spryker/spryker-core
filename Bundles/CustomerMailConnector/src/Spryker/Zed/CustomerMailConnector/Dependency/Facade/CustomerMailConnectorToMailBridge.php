<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerMailConnector\Dependency\Facade;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\SendMailResponsesTransfer;

class CustomerMailConnectorToMailBridge implements CustomerMailConnectorToMailInterface
{

    /**
     * @var \Spryker\Zed\Mail\Business\MailFacade
     */
    protected $mailFacade;

    /**
     * @param \Spryker\Zed\Mail\Business\MailFacade $mailFacade
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
