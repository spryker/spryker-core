<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPayment\Helper;

use Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer;
use Generated\Shared\Transfer\SalesPaymentTransfer;
use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodTypeQuery;
use Orm\Zed\Payment\Persistence\SpySalesPaymentQuery;
use SprykerTest\Shared\Testify\Helper\AbstractHelper;
use SprykerTest\Zed\MessageBroker\Helper\InMemoryMessageBrokerHelperTrait;

class SalesPaymentHelper extends AbstractHelper
{
    use InMemoryMessageBrokerHelperTrait;

    /**
     * @var string
     */
    public const STATE_NEW = 'new';

    /**
     * @var string
     */
    public const STATE_PAYMENT_PENDING = 'payment pending';

    /**
     * @var string
     */
    public const STATE_PAYMENT_AUTHORIZED = 'payment authorized';

    /**
     * @var string
     */
    public const STATE_PAYMENT_AUTHORIZATION_FAILED = 'payment authorization failed';

    /**
     * @var string
     */
    public const STATE_PAYMENT_AUTHORIZATION_CANCELED = 'payment authorization canceled';

    /**
     * @var string
     */
    public const STATE_PAYMENT_CANCELLATION_READY = 'payment cancellation ready';

    /**
     * @var string
     */
    public const STATE_PAYMENT_CANCELLATION_PENDING = 'payment cancellation pending';

    /**
     * @var string
     */
    public const STATE_PAYMENT_CANCELLATION_FAILED = 'payment cancellation failed';

    /**
     * @var string
     */
    public const STATE_PAYMENT_CANCELLED = 'payment cancelled';

    /**
     * @var string
     */
    public const STATE_PAYMENT_CAPTURE_READY = 'payment capture ready';

    /**
     * @var string
     */
    public const STATE_PAYMENT_CAPTURE_PENDING = 'payment capture pending';

    /**
     * @var string
     */
    public const STATE_PAYMENT_FAILED = 'payment failed';

    /**
     * @var string
     */
    public const STATE_PAYMENT_UNDERPAID = 'payment underpaid';

    /**
     * @var string
     */
    public const STATE_PAYMENT_OVERPAID = 'payment overpaid';

    /**
     * @var string
     */
    public const STATE_PAYMENT_CAPTURED = 'payment captured';

    /**
     * @var string
     */
    public const STATE_PAYMENT_CAPTURE_FAILED = 'payment capture failed';

    /**
     * @var string
     */
    public const STATE_COMMISSION_CALCULATED = 'commission calculated';

    /**
     * @var string
     */
    public const STATE_SENT_TO_MERCHANT = 'sent to merchant';

    /**
     * @var string
     */
    public const STATE_MERCHANT_PAYOUT_READY = 'merchant payout ready';

    /**
     * @var string
     */
    public const STATE_DELIVERED = 'delivered';

    /**
     * @var string
     */
    public const STATE_CLOSED = 'closed';

    /**
     * @var string
     */
    public const STATE_CANCELED = 'canceled';

    /**
     * @var string
     */
    public const EVENT_CAPTURE_PAYMENT = 'capture payment';

    /**
     * @var string
     */
    public const EVENT_PAYMENT_OVERPAY_CHECKED = 'payment overpay checked';

    /**
     * @var string
     */
    public const EVENT_DELIVER = 'deliver';

    /**
     * @var string
     */
    public const EVENT_MERCHANT_PAYOUT = 'merchant payout';

    /**
     * @var string
     */
    public const EVENT_CANCEL = 'cancel';

    /**
     * @var string
     */
    public const EVENT_CLOSE = 'close';

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer
     */
    public function haveSalesPaymentMethodTypePersisted(array $seed = []): SalesPaymentMethodTypeTransfer
    {
        $salesPaymentMethodTypeQuery = SpySalesPaymentMethodTypeQuery::create()
            ->filterByPaymentMethod($seed[SalesPaymentMethodTypeTransfer::PAYMENT_METHOD] ?? 'TestPaymentMethod')
            ->filterByPaymentProvider($seed[SalesPaymentMethodTypeTransfer::PAYMENT_PROVIDER] ?? 'TestPaymentProvider');

        $salesPaymentMethodTypeEntity = $salesPaymentMethodTypeQuery->findOneOrCreate();
        $salesPaymentMethodTypeEntity->save();

        $salesPaymentMethodTypeTransfer = new SalesPaymentMethodTypeTransfer();

        return $salesPaymentMethodTypeTransfer->fromArray($salesPaymentMethodTypeEntity->toArray(), true);
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\SalesPaymentTransfer
     */
    public function haveSalesPaymentPersisted(array $seed = []): SalesPaymentTransfer
    {
        $salesPaymentQuery = SpySalesPaymentQuery::create()
            ->filterByFkSalesOrder($seed[SalesPaymentTransfer::FK_SALES_ORDER] ?? rand(1, 10000))
            ->filterByFkSalesPaymentMethodType($seed[SalesPaymentTransfer::FK_PAYMENT_METHOD_TYPE] ?? rand(1, 10000));

        $salesPaymentEntity = $salesPaymentQuery->findOneOrCreate();
        $salesPaymentEntity->fromArray($seed);
        $salesPaymentEntity->save();

        $salesPaymentTransfer = new SalesPaymentTransfer();

        return $salesPaymentTransfer->fromArray($salesPaymentEntity->toArray(), true);
    }

    /**
     * @param string $messageClassName
     * @param array $properties
     *
     * @return void
     */
    public function assertSentMessageProperties(string $messageClassName, array $properties): void
    {
        $this->getInMemoryMessageBrokerHelper()->assertMessagesByCallbackForMessageName(
            function (array $envelopes) use ($properties): void {
                /** @var array<\Symfony\Component\Messenger\Envelope> $envelopes */
                $this->assertCount(1, $envelopes);

                /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $message */
                $message = $envelopes[0]->getMessage();

                $this->assertEquals(
                    $properties,
                    array_replace_recursive(
                        $properties,
                        array_intersect_key($message->toArray(), $properties),
                    ),
                );
            },
            $messageClassName,
        );
    }
}
