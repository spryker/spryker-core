<?php

/**
 * This file is part of the Spryker Platform.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Payolution\Dependency\Facade;

use Generated\Shared\Transfer\MailTransfer;

class PayolutionToMailBridge implements PayolutionToMailInterface
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
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\SendMailResponsesTransfer
     */
    public function sendMail(MailTransfer $mailTransfer)
    {
        return $this->mailFacade->sendMail($mailTransfer);
    }

}
