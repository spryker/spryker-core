<?php


namespace SprykerFeature\Zed\Mail\Business;


use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\MailMailTransferTransfer;

/**
 * @method MailDependencyContainer getDependencyContainer()
 */
class MailFacade extends AbstractFacade
{
    /**
     * @param MailTransfer $mailTransfer
     *
     * @return array
     */
    public function sendMail(MailTransfer $mailTransfer)
    {
        return $this->getDependencyContainer()->getMailSender()->sendMail($mailTransfer);
    }
}
