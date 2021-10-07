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
     * Specification:
     * - Returns a map of the payment methods and state machine's processes names.
     *
     * @api
     *
     * @example The format of returned array is:
     * [
     *    'PAYMENT_METHOD_A' => 'StateMachineProcess01',
     *    'PAYMENT_METHOD_B' => 'StateMachineProcess02',
     * ]
     *
     * @phpstan-return array<string, string>
     *
     * @return array<string>
     */
    public function getPaymentStatemachineMappings()
    {
        return $this->get(PaymentConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING, []);
    }
}
