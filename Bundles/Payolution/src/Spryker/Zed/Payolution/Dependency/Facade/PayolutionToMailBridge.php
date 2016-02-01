<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Dependency\Facade;

use Spryker\Zed\Mail\Business\MailFacade;
use Generated\Shared\Transfer\MailTransfer;

class PayolutionToMailBridge implements PayolutionToMailInterface
{

    /**
     * @var MailFacade
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
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\SendMailResponsesTransfer
     */
    public function sendMail(MailTransfer $mailTransfer)
    {
        return $this->mailFacade->sendMail($mailTransfer);
    }

}
