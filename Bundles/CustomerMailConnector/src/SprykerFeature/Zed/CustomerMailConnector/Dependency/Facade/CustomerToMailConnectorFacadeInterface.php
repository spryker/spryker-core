<?php

namespace SprykerFeature\Zed\CustomerMailConnector\Dependency\Facade;

use Generated\Shared\Transfer\MailTransfer;

//TODO: this file has to also renamed to MailToCustomerConnectorFacadeInterface along the whole CustomerMailConnector bundle (change order to MailCustomer)

interface CustomerToMailConnectorFacadeInterface
{
    /**
     * @param MailTransfer $mailTransfer
     *
     * @return array
     */
    public function sendMail(MailTransfer $mailTransfer);
}
