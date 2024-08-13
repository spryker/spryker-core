<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesPaymentMerchantConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const ITEM_REFERENCE_SEPARATOR = ',';

    /**
     * @var string
     */
    protected const OMS_STATE_PAYMENT_CAPTURE_PENDING = 'payment capture pending';

    /**
     * @var string
     */
    protected const OMS_STATE_PAYMENT_REFUND_PENDING = 'payment refund pending';

    /**
     * @var string
     */
    protected const OMS_STATE_PAYMENT_CAPTURED = 'payment captured';

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
            static::OMS_STATE_PAYMENT_CAPTURE_PENDING,
            static::OMS_STATE_PAYMENT_REFUND_PENDING,
        ];
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig::getCapturePaymentStates()} instead.
     *
     * @return array<string>
     */
    public function getPaymentConfirmationRequestedStates(): array
    {
        return [];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getCapturePaymentStates(): array
    {
        return [
            static::OMS_STATE_PAYMENT_CAPTURED,
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
            static::OMS_STATE_PAYMENT_CAPTURE_PENDING,
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
