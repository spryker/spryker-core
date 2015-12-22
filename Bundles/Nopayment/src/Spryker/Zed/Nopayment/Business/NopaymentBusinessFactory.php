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
     * @return Paid
     */
    public function createNopaymentPaid()
    {
        return new Paid(
            $this->getQueryContainer()
        );
    }

}
