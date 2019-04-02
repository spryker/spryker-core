<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderPaymentsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment\OrderPaymentUpdater;
use Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment\OrderPaymentUpdaterInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @method \Spryker\Glue\OrderPaymentsRestApi\OrderPaymentsRestApiConfig getConfig()
 */
class OrderPaymentsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment\OrderPaymentUpdaterInterface
     */
    public function createOrderPaymentUpdater(): OrderPaymentUpdaterInterface
    {
        return new OrderPaymentUpdater();
    }
}
