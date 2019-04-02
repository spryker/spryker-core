<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderPaymentsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OrderPaymentsRestApi\Business\OrderPayment\OrderPaymentUpdater;
use Spryker\Zed\OrderPaymentsRestApi\Business\OrderPayment\OrderPaymentUpdaterInterface;

/**
 * @method \Spryker\Zed\OrderPaymentsRestApi\OrderPaymentsRestApiConfig getConfig()
 */
class OrderPaymentsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OrderPaymentsRestApi\Business\OrderPayment\OrderPaymentUpdaterInterface
     */
    public function createOrderPaymentUpdater(): OrderPaymentUpdaterInterface
    {
        return new OrderPaymentUpdater();
    }
}
