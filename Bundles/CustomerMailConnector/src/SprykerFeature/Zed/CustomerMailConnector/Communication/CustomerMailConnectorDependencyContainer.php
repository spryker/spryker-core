<?php

namespace SprykerFeature\Zed\CustomerMailConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\Mail\Business\MailFacade;
use Generated\Shared\Transfer\MailMail as MailTransferTransfer;

class CustomerMailConnectorDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return MailFacade
     */
    public function createMailFacade()
    {
        return $this->getLocator()->mail()->facade();
    }

    /**
     * @return MailTransfer
     */
    public function createMailTransfer()
    {
        return new \Generated\Shared\Transfer\MailMailTransfer();
    }
}
