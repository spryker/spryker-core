<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Shared\SelfServicePortal\Helper;

use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Orm\Zed\Payment\Persistence\SpySalesPayment;
use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodTypeQuery;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadataQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\Shipment\Communication\Plugin\Checkout\SalesOrderShipmentSavePlugin;
use SprykerTest\Shared\Sales\Helper\SalesDataHelper as SprykerSalesDataHelper;

class SalesDataHelper extends SprykerSalesDataHelper
{
    public function haveFullOrder(
        array $override = [],
        ?string $stateMachineProcessName = null
    ): SaveOrderTransfer {
        $saveOrderTransfer = $this->haveOrder(
            $override,
            $stateMachineProcessName,
            [new SalesOrderShipmentSavePlugin()],
        );

        $this->updateOrderItems($saveOrderTransfer);
        $this->saveSalesExpense($saveOrderTransfer->getIdSalesOrder());

        $paymentMethod = array_flip((new SalesConfig())->getPaymentMethodStatemachineMapping())[$stateMachineProcessName] ?? null;

        if ($paymentMethod) {
            $this->saveSalesPayment($saveOrderTransfer->getIdSalesOrder(), $paymentMethod);
        }

        return $saveOrderTransfer;
    }

    protected function updateOrderItems(SaveOrderTransfer $saveOrderTransfer): void
    {
        foreach ($saveOrderTransfer->getOrderItems() as $orderItemTransfer) {
            $itemEntity = SpySalesOrderItemQuery::create()->findOneByIdSalesOrderItem($orderItemTransfer->getIdSalesOrderItem());
            if (!$itemEntity) {
                continue;
            }
            $itemEntity->setFkSalesShipment($orderItemTransfer->getShipment()->getIdSalesShipment())->save();
            $this->saveOrderItemMetadata($orderItemTransfer->getIdSalesOrderItem());
        }
    }

    protected function saveOrderItemMetadata(int $idSalesOrderItem): void
    {
        $salesOrderItemMetadata = SpySalesOrderItemMetadataQuery::create()->findOneByFkSalesOrderItem($idSalesOrderItem);
        if ($salesOrderItemMetadata) {
            return;
        }

        (new SpySalesOrderItemMetadata())
            ->setFkSalesOrderItem($idSalesOrderItem)
            ->setSuperAttributes('{"color":"Black"}')
            ->save();
    }

    protected function saveSalesExpense(int $idSalesOrder): void
    {
        $salesExpenseEntity = SpySalesExpenseQuery::create()->findOneByFkSalesOrder($idSalesOrder);
        if ($salesExpenseEntity) {
            return;
        }

        $salesExpenseEntity = (new SpySalesExpense())
            ->setFkSalesOrder($idSalesOrder)
            ->setName('test')
            ->setType('SHIPMENT_EXPENSE_TYPE')
            ->setGrossPrice(100);

        $salesExpenseEntity->save();

        SpySalesShipmentQuery::create()
            ->findOneByFkSalesOrder($idSalesOrder)
            ->setFkSalesExpense($salesExpenseEntity->getIdSalesExpense())
            ->save();
    }

    protected function saveSalesPayment(int $idSalesOrder, string $paymentMethod): void
    {
        $paymentMethodEntity = SpyPaymentMethodQuery::create()->findOneByPaymentMethodKey($paymentMethod);
        if (!$paymentMethodEntity) {
            return;
        }
        $paymentProviderEntity = $paymentMethodEntity->getSpyPaymentProvider();
        $salesPaymentMethodTypeEntity = SpySalesPaymentMethodTypeQuery::create()->findOneByPaymentProvider($paymentProviderEntity->getPaymentProviderKey());
        (new SpySalesPayment())
            ->setFkSalesOrder($idSalesOrder)
            ->setFkSalesPaymentMethodType($salesPaymentMethodTypeEntity->getIdSalesPaymentMethodType())
            ->setAmount(100)
            ->save();
    }
}
