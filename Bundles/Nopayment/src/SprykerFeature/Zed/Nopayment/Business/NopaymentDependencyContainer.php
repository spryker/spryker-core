<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Nopayment\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Nopayment\Business\Nopayment\Paid;
use SprykerFeature\Zed\Nopayment\Persistence\NopaymentQueryContainer;

class NopaymentDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return NopaymentQueryContainer
     */
    public function locateQueryContainer()
    {
        return $this->getLocator()->nopayment()->queryContainer();
    }

    /**
     * @return Paid
     */
    public function createNopaymentPaid()
    {
        $queryContainer = $this->locateQueryContainer();

        return $this->getFactory()
            ->createNopaymentPaid(
                $queryContainer,
                $this->getLocator()
            );
    }
}