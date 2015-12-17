<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Nopayment\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Nopayment\Persistence\NopaymentQueryContainer;
use Spryker\Zed\Nopayment\Business\NopaymentFacade;

class NopaymentCommunicationFactory extends AbstractCommunicationFactory
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
