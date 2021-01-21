<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrderDataExport;

use Codeception\Actor;
use DateTime;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportConnectionConfigurationTransfer;
use Generated\Shared\Transfer\DataExportFormatConfigurationTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
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
 * @method \Spryker\Zed\MerchantSalesOrderDataExport\Business\MerchantSalesOrderDataExportFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantSalesOrderDataExportBusinessTester extends Actor
{
    use _generated\MerchantSalesOrderDataExportBusinessTesterActions;

    /**
     * @uses \Spryker\Service\DataExport\Writer\DataExportLocalWriter::LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR
     */
    protected const LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR = 'export_root_dir';
    protected const EXPORT_ROOT_DIR = '{application_root_dir}/data/export';

    protected const DESTINATION_TEMPLATE = 'merchants/{merchant_name}/merchant-orders/{data_entity}s_{store_name}_{timestamp}.{extension}';
    protected const FORMATTER_TYPE = 'csv';
    protected const CONNECTION_TYPE = 'local';
    protected const EXTENSION = 'csv';

    /**
     * @param string $merchantReference
     *
     * @return void
     */
    public function createMerchantOrder(string $merchantReference): void
    {
        $salesOrder = $this->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $this->haveMerchantOrder([
            MerchantOrderTransfer::ID_ORDER => $salesOrder->getIdSalesOrder(),
            MerchantOrderTransfer::MERCHANT_REFERENCE => $merchantReference,
        ]);
    }

    /**
     * @param string $merchantReference
     *
     * @return void
     */
    public function createMerchantOrderItem(string $merchantReference): void
    {
        $salesOrder = $this->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $merchantOrderTransfer = $this->haveMerchantOrder([
            MerchantOrderTransfer::ID_ORDER => $salesOrder->getIdSalesOrder(),
            MerchantOrderTransfer::MERCHANT_REFERENCE => $merchantReference,
        ]);
        $salesOrderItem = $this->createSalesOrderItemForOrder($salesOrder->getIdSalesOrder());
        $this->haveMerchantOrderItem([
            MerchantOrderItemTransfer::ID_ORDER_ITEM => $salesOrderItem->getIdSalesOrderItem(),
            MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
        ]);
    }

    /**
     * @param string $merchantReference
     *
     * @return void
     */
    public function createMerchantOrderExpense(string $merchantReference): void
    {
        $salesOrder = $this->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $this->haveMerchantOrder([
            MerchantOrderTransfer::ID_ORDER => $salesOrder->getIdSalesOrder(),
            MerchantOrderTransfer::MERCHANT_REFERENCE => $merchantReference,
        ]);
        $this->haveOrderExpense($salesOrder->getIdSalesOrder(), $merchantReference);
    }

    /**
     * @param int $idSalesOrder
     * @param string $merchantReference
     *
     * @return void
     */
    protected function haveOrderExpense(int $idSalesOrder, string $merchantReference): void
    {
        $expenseTransfer = (new ExpenseBuilder([
            ExpenseTransfer::FK_SALES_ORDER => $idSalesOrder,
            ExpenseTransfer::MERCHANT_REFERENCE => $merchantReference,
        ]))->build();

        $salesExpenseEntity = SpySalesExpenseQuery::create()
            ->filterByFkSalesOrder($idSalesOrder)
            ->findOneOrCreate();

        $salesExpenseEntity->fromArray($expenseTransfer->toArray());
        $salesExpenseEntity->setGrossPrice($expenseTransfer->getSumGrossPrice());
        $salesExpenseEntity->setNetPrice($expenseTransfer->getSumNetPrice());
        $salesExpenseEntity->setPrice($expenseTransfer->getSumPrice());
        $salesExpenseEntity->setTaxAmount($expenseTransfer->getSumTaxAmount());
        $salesExpenseEntity->setDiscountAmountAggregation($expenseTransfer->getSumDiscountAmountAggregation());
        $salesExpenseEntity->setPriceToPayAggregation($expenseTransfer->getSumPriceToPayAggregation());

        $salesExpenseEntity->save();
    }

    /**
     * @param string $dataEntity
     * @param int $timestamp
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function createDataExportConfigurationTransfer(string $dataEntity, int $timestamp): DataExportConfigurationTransfer
    {
        $dataExportFormatConfigurationTransfer = (new DataExportFormatConfigurationTransfer())
            ->setType(static::FORMATTER_TYPE);
        $dataExportConnectionConfigurationTransfer = (new DataExportConnectionConfigurationTransfer())
            ->setType(static::CONNECTION_TYPE)
            ->setParams([
                static::LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR => static::EXPORT_ROOT_DIR,
            ]);

        return (new DataExportConfigurationTransfer())
            ->setDataEntity($dataEntity)
            ->setDestination(static::DESTINATION_TEMPLATE)
            ->setFormat($dataExportFormatConfigurationTransfer)
            ->setConnection($dataExportConnectionConfigurationTransfer)
            ->setFilterCriteria([
                'merchant_order_created_at' => [
                    'type' => 'between',
                    'from' => (new DateTime('-1 minute'))->format('Y-m-d H:i:s'),
                    'to' => (new DateTime('+1 minute'))->format('Y-m-d H:i:s'),
                ],
                'merchant_order_updated_at' => [
                    'type' => 'between',
                    'from' => (new DateTime('-1 minute'))->format('Y-m-d H:i:s'),
                    'to' => (new DateTime('+1 minute'))->format('Y-m-d H:i:s'),
                ],
                'store_name' => ['DE'],
            ])
            ->setHooks([
                'data_entity' => $dataEntity,
                'timestamp' => $timestamp,
                'extension' => static::EXTENSION,
            ]);
    }
}
