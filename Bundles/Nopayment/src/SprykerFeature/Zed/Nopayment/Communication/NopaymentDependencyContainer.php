<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Nopayment\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\NopaymentCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Nopayment\Persistence\NopaymentQueryContainer;
use SprykerFeature\Zed\Nopayment\Business\NopaymentFacade;

/**
* @method NopaymentCommunication getFactory()
*/
class NopaymentDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return NopaymentQueryContainer
     */
    public function createQueryContainer()
    {
        return $this->getLocator()->nopayment()->queryContainer();
    }

    /**
     * @return NopaymentFacade
     */
    public function createFacade()
    {
        return $this->getLocator()->nopayment()->facade();
    }

}
