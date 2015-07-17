<?php

namespace SprykerFeature\Zed\CustomerMailConnector\Dependency\Facade;

use Generated\Shared\Transfer\MailTransfer;

interface CustomerMailConnectorToMailInterface
{

    /**
     * @param MailTransfer $mailTransfer
     *
     * @return array
     */
    public function sendMail(MailTransfer $mailTransfer);

}
