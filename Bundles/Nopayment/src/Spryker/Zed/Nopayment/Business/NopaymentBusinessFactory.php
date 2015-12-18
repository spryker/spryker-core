<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Nopayment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Nopayment\Business\Nopayment\Paid;
use Spryker\Zed\Nopayment\Persistence\NopaymentQueryContainer;
use Spryker\Zed\Nopayment\NopaymentConfig;

/**
 * @method NopaymentConfig getConfig()
 * @method NopaymentQueryContainer getQueryContainer()
 */
class NopaymentBusinessFactory extends AbstractBusinessFactory
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

        return new Paid(
                $queryContainer,
                $this->getLocator()
            );
    }

}
