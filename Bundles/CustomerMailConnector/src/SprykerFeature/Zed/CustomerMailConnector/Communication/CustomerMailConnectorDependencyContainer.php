<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Mail\Business\MailFacade;

class CustomerMailConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
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
        return new \Generated\Shared\Transfer\MailTransfer();
    }

}
