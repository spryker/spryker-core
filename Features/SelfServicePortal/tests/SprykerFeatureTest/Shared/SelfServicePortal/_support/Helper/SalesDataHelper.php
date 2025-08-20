<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Shared\SelfServicePortal\Helper;

use DateTime;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Orm\Zed\Payment\Persistence\SpySalesPayment;
use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodTypeQuery;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadataQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductClass;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesProductClassQuery;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\Shipment\Communication\Plugin\Checkout\SalesOrderShipmentSavePlugin;
use SprykerTest\Shared\Sales\Helper\SalesDataHelper as SprykerSalesDataHelper;

class SalesDataHelper extends SprykerSalesDataHelper
{
    /**
     * @var string
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @var string
     */
    protected const DEFAULT_EXPENSE_NAME = 'test';

    /**
     * @var int
     */
    protected const DEFAULT_EXPENSE_AMOUNT = 100;

    /**
     * @var string
     */
    protected const DEFAULT_SUPER_ATTRIBUTES = '{"color":"Black"}';

    /**
     * @var string
     */
    protected const OVERRIDE_KEY_ITEM = 'item';

    /**
     * @var string
     */
    protected const OVERRIDE_KEY_SKU = 'sku';

    /**
     * @var string
     */
    protected const OVERRIDE_KEY_PRODUCT_CLASSES = 'productClasses';

    /**
     * @var string
     */
    protected const OVERRIDE_KEY_SCHEDULED_AT = 'scheduledAt';

    /**
     * @var string
     */
    protected const OVERRIDE_KEY_CUSTOMER_REFERENCE = 'customerReference';

    /**
     * @param list<mixed> $override
     * @param string|null $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
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

        if (isset($override[static::OVERRIDE_KEY_ITEM][static::OVERRIDE_KEY_SKU])) {
            $this->handleOrderItemProductClasses($override);
            $this->handleOrderItemScheduling($override);
        }

        $this->handleCompanyAssignment($saveOrderTransfer, $override);
        $this->handlePaymentMethodAssignment($saveOrderTransfer, $stateMachineProcessName);

        return $saveOrderTransfer;
    }

    /**
     * @param list<mixed> $override
     *
     * @return void
     */
    protected function handleOrderItemProductClasses(array $override): void
    {
        if (
            !isset($override[static::OVERRIDE_KEY_ITEM][static::OVERRIDE_KEY_PRODUCT_CLASSES]) ||
            !is_array($override[static::OVERRIDE_KEY_ITEM][static::OVERRIDE_KEY_PRODUCT_CLASSES])
        ) {
            return;
        }

        $salesOrderItemEntity = $this->getSalesOrderItemQuery()->findOneBySku($override[static::OVERRIDE_KEY_ITEM][static::OVERRIDE_KEY_SKU]);
        if (!$salesOrderItemEntity) {
            return;
        }

        foreach ($override[static::OVERRIDE_KEY_ITEM][static::OVERRIDE_KEY_PRODUCT_CLASSES] as $productClassName) {
            $this->createOrderItemProductClassRelation($salesOrderItemEntity, $productClassName);
        }
    }

    protected function createOrderItemProductClassRelation(SpySalesOrderItem $salesOrderItemEntity, string $productClassName): void
    {
        $productClassEntity = $this->getSalesProductClassQuery()
            ->filterByName($productClassName)
            ->findOneOrCreate();

        if ($productClassEntity->isNew()) {
            $productClassEntity->save();
        }

        if (!$productClassEntity) {
            return;
        }

        (new SpySalesOrderItemProductClass())
            ->setFkSalesOrderItem($salesOrderItemEntity->getIdSalesOrderItem())
            ->setFkSalesProductClass($productClassEntity->getIdSalesProductClass())
            ->save();
    }

    /**
     * @param list<mixed> $override
     *
     * @return void
     */
    protected function handleOrderItemScheduling(array $override): void
    {
        if (!isset($override[static::OVERRIDE_KEY_ITEM][static::OVERRIDE_KEY_SCHEDULED_AT])) {
            return;
        }

        $salesOrderItemEntity = $this->getSalesOrderItemQuery()->findOneBySku($override[static::OVERRIDE_KEY_ITEM][static::OVERRIDE_KEY_SKU]);
        if (!$salesOrderItemEntity) {
            return;
        }

        $salesOrderItemMetadataEntity = $this->getSalesOrderItemMetadataQuery()
            ->findOneByFkSalesOrderItem($salesOrderItemEntity->getIdSalesOrderItem());

        if (!$salesOrderItemMetadataEntity) {
            return;
        }

        $scheduledDateTime = new DateTime($override[static::OVERRIDE_KEY_ITEM][static::OVERRIDE_KEY_SCHEDULED_AT]);
        $salesOrderItemMetadataEntity->setScheduledAt($scheduledDateTime)->save();
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param list<mixed> $override
     *
     * @return void
     */
    protected function handleCompanyAssignment(SaveOrderTransfer $saveOrderTransfer, array $override): void
    {
        if (!isset($override[static::OVERRIDE_KEY_CUSTOMER_REFERENCE])) {
            return;
        }

        $companyUserEntity = $this->getCompanyUserQuery()
            ->useCustomerQuery()
            ->filterByCustomerReference($override[static::OVERRIDE_KEY_CUSTOMER_REFERENCE])
            ->endUse()
            ->findOne();

        if (!$companyUserEntity) {
            return;
        }

        $this->getSalesOrderQuery()
            ->findOneByIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setCompanyUuid($companyUserEntity->getCompany()->getUuid())
            ->setCompanyBusinessUnitUuid($companyUserEntity->getCompanyBusinessUnit()->getUuid())
            ->save();
    }

    protected function handlePaymentMethodAssignment(SaveOrderTransfer $saveOrderTransfer, ?string $stateMachineProcessName): void
    {
        if (!$stateMachineProcessName) {
            return;
        }

        $paymentMethodStatemachineMapping = (new SalesConfig())->getPaymentMethodStatemachineMapping();
        $paymentMethod = array_flip($paymentMethodStatemachineMapping)[$stateMachineProcessName] ?? null;

        if ($paymentMethod) {
            $this->saveSalesPayment($saveOrderTransfer->getIdSalesOrder(), $paymentMethod);
        }
    }

    protected function updateOrderItems(SaveOrderTransfer $saveOrderTransfer): void
    {
        foreach ($saveOrderTransfer->getOrderItems() as $orderItemTransfer) {
            $salesOrderItemEntity = $this->getSalesOrderItemQuery()
                ->findOneByIdSalesOrderItem($orderItemTransfer->getIdSalesOrderItem());

            if (!$salesOrderItemEntity) {
                continue;
            }

            $salesOrderItemEntity
                ->setFkSalesShipment($orderItemTransfer->getShipment()->getIdSalesShipment())
                ->save();

            $this->saveOrderItemMetadata($orderItemTransfer->getIdSalesOrderItem());
        }
    }

    protected function saveOrderItemMetadata(int $idSalesOrderItem): void
    {
        $salesOrderItemMetadataEntity = $this->getSalesOrderItemMetadataQuery()
            ->findOneByFkSalesOrderItem($idSalesOrderItem);

        if ($salesOrderItemMetadataEntity) {
            return;
        }

        (new SpySalesOrderItemMetadata())
            ->setFkSalesOrderItem($idSalesOrderItem)
            ->setSuperAttributes(static::DEFAULT_SUPER_ATTRIBUTES)
            ->save();
    }

    protected function saveSalesExpense(int $idSalesOrder): void
    {
        $salesExpenseEntity = $this->getSalesExpenseQuery()->findOneByFkSalesOrder($idSalesOrder);
        if ($salesExpenseEntity) {
            return;
        }

        $salesExpenseEntity = (new SpySalesExpense())
            ->setFkSalesOrder($idSalesOrder)
            ->setName(static::DEFAULT_EXPENSE_NAME)
            ->setType(static::SHIPMENT_EXPENSE_TYPE)
            ->setGrossPrice(static::DEFAULT_EXPENSE_AMOUNT);

        $salesExpenseEntity->save();

        $this->getSalesShipmentQuery()
            ->findOneByFkSalesOrder($idSalesOrder)
            ->setFkSalesExpense($salesExpenseEntity->getIdSalesExpense())
            ->save();
    }

    protected function saveSalesPayment(int $idSalesOrder, string $paymentMethod): void
    {
        $paymentMethodEntity = $this->getPaymentMethodQuery()->findOneByPaymentMethodKey($paymentMethod);
        if (!$paymentMethodEntity) {
            return;
        }

        $paymentProviderEntity = $paymentMethodEntity->getSpyPaymentProvider();
        $salesPaymentMethodTypeEntity = $this->getSalesPaymentMethodTypeQuery()
            ->findOneByPaymentProvider($paymentProviderEntity->getPaymentProviderKey());

        if (!$salesPaymentMethodTypeEntity) {
            return;
        }

        (new SpySalesPayment())
            ->setFkSalesOrder($idSalesOrder)
            ->setFkSalesPaymentMethodType($salesPaymentMethodTypeEntity->getIdSalesPaymentMethodType())
            ->setAmount(static::DEFAULT_EXPENSE_AMOUNT)
            ->save();
    }

    protected function getSalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return SpySalesOrderItemQuery::create();
    }

    protected function getSalesProductClassQuery(): SpySalesProductClassQuery
    {
        return SpySalesProductClassQuery::create();
    }

    protected function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return SpyCompanyUserQuery::create();
    }

    protected function getSalesOrderQuery(): SpySalesOrderQuery
    {
        return SpySalesOrderQuery::create();
    }

    protected function getSalesOrderItemMetadataQuery(): SpySalesOrderItemMetadataQuery
    {
        return SpySalesOrderItemMetadataQuery::create();
    }

    protected function getSalesExpenseQuery(): SpySalesExpenseQuery
    {
        return SpySalesExpenseQuery::create();
    }

    protected function getSalesShipmentQuery(): SpySalesShipmentQuery
    {
        return SpySalesShipmentQuery::create();
    }

    protected function getPaymentMethodQuery(): SpyPaymentMethodQuery
    {
        return SpyPaymentMethodQuery::create();
    }

    protected function getSalesPaymentMethodTypeQuery(): SpySalesPaymentMethodTypeQuery
    {
        return SpySalesPaymentMethodTypeQuery::create();
    }
}
