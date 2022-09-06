<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesPaymentConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const OMS_STATE_PAYMENT_CONFIRMATION_PENDING = 'payment confirmation pending';

    /**
     * @var string
     */
    protected const OMS_STATE_PAYMENT_REFUND_PENDING = 'payment refund pending';

    /**
     * @var string
     */
    protected const OMS_STATE_PAYMENT_CONFIRMED = 'payment confirmed';

    /**
     * @var string
     */
    protected const OMS_STATE_PAYMENT_REFUNDED = 'payment refunded';

    /**
     * @var string
     */
    protected const OMS_STATE_PAYMENT_CANCELED = 'canceled';

    /**
     * @api
     *
     * @return array<string>
     */
    public function getPaymentCaptureRequestBlockingStates(): array
    {
        return [
            static::OMS_STATE_PAYMENT_CONFIRMATION_PENDING,
            static::OMS_STATE_PAYMENT_REFUND_PENDING,
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getPaymentConfirmationRequestedStates(): array
    {
        return [
            static::OMS_STATE_PAYMENT_CONFIRMED,
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getPaymentRefundRequestBlockingStates(): array
    {
        return [
            static::OMS_STATE_PAYMENT_CONFIRMATION_PENDING,
            static::OMS_STATE_PAYMENT_REFUND_PENDING,
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getItemRefusedStates(): array
    {
        return [
            static::OMS_STATE_PAYMENT_REFUNDED,
            static::OMS_STATE_PAYMENT_CANCELED,
        ];
    }
}
