<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment;

use Spryker\Shared\Payment\PaymentConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class PaymentConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getPaymentStatemachineMappings()
    {
        return $this->get(PaymentConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING);
    }
}
