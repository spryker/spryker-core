<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\AuthMailConnector\Dependency\Facade;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\SendMailResponsesTransfer;
use Spryker\Zed\Mail\Business\MailFacade;

class AuthMailConnectorToMailBridge implements AuthMailConnectorToMailInterface
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

}
