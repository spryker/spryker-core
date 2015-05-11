<?php

namespace SprykerFeature\Zed\Nopayment\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Nopayment\Business\Nopayment\Paid;

class NopaymentDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return Paid
     */
    public function createNopaymentPaid()
    {
        $queryContainer = $this->getLocator()->nopayment()->queryContainer();

        return $this->getFactory()
            ->createNopaymentPaid(
                $queryContainer,
                $this->getLocator()
            );
    }
}