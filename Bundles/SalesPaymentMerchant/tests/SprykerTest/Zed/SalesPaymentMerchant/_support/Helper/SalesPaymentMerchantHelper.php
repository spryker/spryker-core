<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPaymentMerchant\Helper;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayout;
use Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutQuery;
use Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutReversal;
use Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutReversalQuery;
use Ramsey\Uuid\Uuid;
use SprykerTest\Shared\Testify\Helper\AbstractHelper;

class SalesPaymentMerchantHelper extends AbstractHelper
{
    /**
     * @param string|null $merchantReference
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function createSalesOrderItemEntity(?string $merchantReference = null): SpySalesOrderItem
    {
        $orderItemReference = Uuid::uuid4()->toString();

        $salesOrderItemEntity = new SpySalesOrderItem();
        $salesOrderItemEntity
            ->setMerchantReference($merchantReference)
            ->setOrderItemReference($orderItemReference)
            ->setAmount(1000);

        return $salesOrderItemEntity;
    }

    /**
     * @param array $seed
     *
     * @return \Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayout
     */
    public function haveSalesPaymentMerchantPayoutPersisted(array $seed): SpySalesPaymentMerchantPayout
    {
        $spySalesMerchantPayoutEntity = new SpySalesPaymentMerchantPayout();
        $spySalesMerchantPayoutEntity->fromArray($seed);
        $spySalesMerchantPayoutEntity->save();

        return $spySalesMerchantPayoutEntity;
    }

    /**
     * @param array $seed
     *
     * @return \Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutReversal
     */
    public function haveSalesPaymentMerchantPayoutReversalPersisted(array $seed): SpySalesPaymentMerchantPayoutReversal
    {
        $spySalesPaymentMerchantPayoutReversal = new SpySalesPaymentMerchantPayoutReversal();
        $spySalesPaymentMerchantPayoutReversal->fromArray($seed);
        $spySalesPaymentMerchantPayoutReversal->save();

        return $spySalesPaymentMerchantPayoutReversal;
    }

    /**
     * @param array $seed
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function createSalesOrderEntity(array $seed): SpySalesOrder
    {
        $orderReference = Uuid::uuid4()->toString();
        $idSalesOrder = rand(1000, 9999);
        $orderItems = $seed[OrderTransfer::ITEMS] ?? [];

        $salesOrderEntity = new SpySalesOrder();
        $salesOrderEntity
            ->setIdSalesOrder($seed[OrderTransfer::ID_SALES_ORDER] ?? $idSalesOrder)
            ->setOrderReference($seed[OrderTransfer::ORDER_REFERENCE] ?? $orderReference);

        foreach ($orderItems as $salesOrderItem) {
            $salesOrderEntity->addItem($salesOrderItem);
        }

        return $salesOrderEntity;
    }

    /**
     * @param string $merchantReference
     * @param string $orderReference
     * @param array $salesOrderItems
     *
     * @return void
     */
    public function assertSalesPaymentMerchantPayoutEntity(string $merchantReference, string $orderReference, array $salesOrderItems): void
    {
        $salesPaymentMerchantPayoutEntity = SpySalesPaymentMerchantPayoutQuery::create()
            ->filterByMerchantReference($merchantReference)
            ->filterByOrderReference($orderReference)
            ->findOne();

        $this->assertNotNull($salesPaymentMerchantPayoutEntity, 'Could not find a Payout Entity in the Database.');

        $itemReferences = explode(',', $salesPaymentMerchantPayoutEntity->getItemReferences());

        foreach ($salesOrderItems as $salesOrderItem) {
            $this->assertContains($salesOrderItem->getOrderItemReference(), $itemReferences);
        }
    }

    /**
     * @param string $merchantReference
     * @param string $orderReference
     * @param array $salesOrderItems
     *
     * @return void
     */
    public function assertSalesPaymentMerchantRefundEntity(string $merchantReference, string $orderReference, array $salesOrderItems): void
    {
        $salesPaymentMerchantRefundEntity = SpySalesPaymentMerchantPayoutReversalQuery::create()
            ->filterByMerchantReference($merchantReference)
            ->filterByOrderReference($orderReference)
            ->findOne();

        $this->assertNotNull($salesPaymentMerchantRefundEntity, 'Could not find a Refund Entity in the Database.');

        $itemReferences = explode(',', $salesPaymentMerchantRefundEntity->getItemReferences());

        foreach ($salesOrderItems as $salesOrderItem) {
            $this->assertContains($salesOrderItem->getOrderItemReference(), $itemReferences);
        }
    }
}
