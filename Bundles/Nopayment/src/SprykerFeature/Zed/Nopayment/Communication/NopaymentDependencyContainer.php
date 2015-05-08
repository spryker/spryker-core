<?php

namespace SprykerFeature\Zed\Nopayment\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\NopaymentCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\Nopayment\Persistence\NopaymentQueryContainer;
use SprykerFeature\Zed\Nopayment\Business\NopaymentFacade;

/**
* @method NopaymentCommunication getFactory()
*/
class NopaymentDependencyContainer extends AbstractDependencyContainer
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
