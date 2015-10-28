<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Mail\Business;

use Generated\Shared\Mail\SendMailResponsesInterface;
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
     * @return SendMailResponsesInterface
     */
    public function sendMail(MailTransfer $mailTransfer)
    {
        return $this->getDependencyContainer()->getMailSender()->sendMail($mailTransfer);
    }

    /**
     * @param SendMailResponsesInterface $mailResponses
     *
     * @return bool
     */
    public function isMailSent(SendMailResponsesInterface $mailResponses)
    {
        return $this->getDependencyContainer()->getMailSender()->isMailSent($mailResponses);
    }

}
