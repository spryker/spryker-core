<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication;

use Generated\Shared\Transfer\MailTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Mail\Business\MailFacade;
use SprykerFeature\Zed\CustomerMailConnector\CustomerMailConnectorDependencyProvider;

class CustomerMailConnectorDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return MailFacade
     */
    public function createMailFacade()
    {
        return $this->getProvidedDependency(CustomerMailConnectorDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return MailTransfer
     */
    public function createMailTransfer()
    {
        return new MailTransfer();
    }

}
