<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Dependency;

interface PaymentEvents
{
    /**
     * Specification:
     * - Represents creation of PBC payment method.
     *
     * @api
     *
     * @var string
     */
    public const PBC_PAYMENT_METHOD_ADDED = 'PaymentMethod.Added';

    /**
     * Specification:
     * - Represents deletion of PBC payment method.
     *
     * @api
     *
     * @var string
     */
    public const PBC_PAYMENT_METHOD_DELETED = 'PaymentMethod.Deleted';

    /**
     * Specification:
     * - Name of the event that is triggered in order to cancel pre-authorized money to the customer.
     *
     * @api
     *
     * @var string
     */
    public const EVENT_TRIGGERED_ORDER_PAYMENT_CANCEL_RESERVATION_REQUESTED = 'Order.PaymentCancelReservationRequested';

    /**
     * Specification:
     * - Name of the event that is triggered in order to confirm the payment of some amount of money at the payment system.
     *
     * @api
     *
     * @var string
     */
    public const EVENT_TRIGGERED_ORDER_PAYMENT_CONFIRMATION_REQUESTED = 'Order.PaymentConfirmationRequested';

    /**
     * Specification:
     * - Name of the event that is triggered in order to refund some amount of money to the customer.
     *
     * @api
     *
     * @var string
     */
    public const EVENT_TRIGGERED_ORDER_PAYMENT_REFUND_REQUESTED = 'Order.PaymentRefundRequested';

    /**
     * Specification:
     * - Name of the event that was triggered after successful pre-authorization of money at the customer's card.
     *
     * @api
     *
     * @var string
     */
    public const EVENT_LISTENED_ORDER_PAYMENT_PREAUTHORIZED = 'Order.PaymentPreauthorized';

    /**
     * Specification:
     * - Name of the event that was triggered after failed pre-authorization of money at the customer's card.
     *
     * @api
     *
     * @var string
     */
    public const EVENT_LISTENED_ORDER_PAYMENT_PREAUTHORIZATION_FAILED = 'Order.PaymentPreauthorizationWasFailed';

    /**
     * Specification:
     * - Name of the event that was triggered after the successful payment confirmation.
     *
     * @api
     *
     * @var string
     */
    public const EVENT_LISTENED_ORDER_PAYMENT_CONFIRMED = 'Order.PaymentConfirmed';

    /**
     * Specification:
     * - Name of the event that was triggered after the failed payment confirmation.
     *
     * @api
     *
     * @var string
     */
    public const EVENT_LISTENED_ORDER_PAYMENT_CONFIRMATION_FAILED = 'Order.PaymentConfirmationWasFailed';

    /**
     * Specification:
     * - Name of the event that was triggered after the successful refund of money to the customer.
     *
     * @api
     *
     * @var string
     */
    public const EVENT_LISTENED_ORDER_PAYMENT_REFUNDED = 'Order.PaymentRefunded';

    /**
     * Specification:
     * - Name of the event that was triggered after failed refund of money to the customer.
     *
     * @api
     *
     * @var string
     */
    public const EVENT_LISTENED_ORDER_PAYMENT_REFUND_FAILED = 'Order.PaymentRefundWasFailed';

    /**
     * Specification:
     * - Name of the event that was triggered after the successful cancel of pre-authorized money to the customer.
     *
     * @api
     *
     * @var string
     */
    public const EVENT_LISTENED_ORDER_PAYMENT_RESERVATION_CANCELED = 'Order.PaymentReservationCanceled';

    /**
     * Specification:
     * - Name of the event that was triggered after failed cancel of pre-authorized money to the customer.
     *
     * @api
     *
     * @var string
     */
    public const EVENT_LISTENED_ORDER_PAYMENT_CANCEL_RESERVATION_FAILED = 'Order.PaymentCancelReservationWasFailed';
}
