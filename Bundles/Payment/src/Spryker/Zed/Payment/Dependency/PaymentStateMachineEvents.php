<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Dependency;

interface PaymentStateMachineEvents
{
    /**
     * @var string
     */
    public const OMS_PAYMENT_AUTHORIZATION_SUCCESSFUL = 'payment authorization successful';

    /**
     * @var string
     */
    public const OMS_PAYMENT_AUTHORIZATION_FAILED = 'payment authorization failed';

    /**
     * @var string
     */
    public const OMS_PAYMENT_CONFIRMATION_SUCCESSFUL = 'payment confirmation successful';

    /**
     * @var string
     */
    public const OMS_PAYMENT_CONFIRMATION_FAILED = 'payment confirmation failed';

    /**
     * @var string
     */
    public const OMS_PAYMENT_REFUND_SUCCESSFUL = 'payment refund successful';

    /**
     * @var string
     */
    public const OMS_PAYMENT_REFUND_FAILED = 'payment refund failed';

    /**
     * @var string
     */
    public const OMS_PAYMENT_CANCEL_RESERVATION_SUCCESSFUL = 'reservation cancellation successful';

    /**
     * @var string
     */
    public const OMS_PAYMENT_CANCEL_RESERVATION_FAILED = 'reservation cancellation failed';
}
