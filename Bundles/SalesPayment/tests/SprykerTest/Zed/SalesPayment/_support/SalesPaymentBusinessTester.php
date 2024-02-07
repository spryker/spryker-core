<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPayment;

use Codeception\Actor;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Payment\Persistence\SpySalesPaymentQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Zed\SalesPayment\PHPMD)
 *
 * @method \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface getFacade(?string $moduleName = NULL)
 */
class SalesPaymentBusinessTester extends Actor
{
    use _generated\SalesPaymentBusinessTesterActions;

    /**
     * @var string
     */
    public const KEY_EXPECTED_AMOUNT = 'expectedAmount';

    /**
     * @var string
     */
    public const KEY_ORDER = 'order';

    /**
     * @var string
     */
    public const KEY_SENT_ITEM_IDS = 'sentItemIds';

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return void
     */
    public function assertIsSalesPaymentSaved(PaymentTransfer $paymentTransfer): void
    {
        $this->assertNotEmpty($paymentTransfer->getIdSalesPayment());
        $salesPaymentEntity = SpySalesPaymentQuery::create()
            ->findOneByIdSalesPayment($paymentTransfer->getIdSalesPayment());

        $this->assertNotNull($salesPaymentEntity);
        $salesPaymentMethodTypeEntity = $salesPaymentEntity->getSalesPaymentMethodType();

        $this->assertEquals($paymentTransfer->getPaymentProvider(), $salesPaymentMethodTypeEntity->getPaymentProvider());
        $this->assertEquals($paymentTransfer->getPaymentMethod(), $salesPaymentMethodTypeEntity->getPaymentMethod());
        $this->assertEquals($paymentTransfer->getAmount(), $salesPaymentEntity->getAmount());
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTransfer(array $seedData): OrderTransfer
    {
        return (new OrderBuilder($seedData))->build();
    }
}
