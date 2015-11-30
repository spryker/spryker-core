<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Mail\Business;

use Generated\Shared\Transfer\SendMailResponsesTransfer;
use Generated\Shared\Transfer\MailTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method MailDependencyContainer getDependencyContainer()
 */
class MailFacade extends AbstractFacade
{

    /**
     * @param MailTransfer $mailTransfer
     *
     * @return SendMailResponsesTransfer
     */
    public function sendMail(MailTransfer $mailTransfer)
    {
        return $this->getDependencyContainer()->getMailSender()->sendMail($mailTransfer);
    }

    /**
     * @param SendMailResponsesTransfer $mailResponses
     *
     * @return bool
     */
    public function isMailSent(SendMailResponsesTransfer $mailResponses)
    {
        return $this->getDependencyContainer()->getMailSender()->isMailSent($mailResponses);
    }

}
