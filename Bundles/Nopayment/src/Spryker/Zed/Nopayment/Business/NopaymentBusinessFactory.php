<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Nopayment\Business\Nopayment\Paid;

/**
 * @method \Spryker\Zed\Nopayment\NopaymentConfig getConfig()
 * @method \Spryker\Zed\Nopayment\Persistence\NopaymentQueryContainer getQueryContainer()
 */
class NopaymentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Nopayment\Business\Nopayment\Paid
     */
    public function createNopaymentPaid()
    {
        return new Paid(
            $this->getQueryContainer()
        );
    }
}
