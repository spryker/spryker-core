<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrderDataExport\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportConnectionConfigurationTransfer;
use Generated\Shared\Transfer\DataExportFormatConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantSalesOrderDataExport
 * @group Business
 * @group Facade
 * @group MerchantSalesOrderDataExportFacadeTest
 * Add your own group annotations below this line
 */
class MerchantSalesOrderDataExportFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Service\DataExport\Writer\DataExportLocalWriter::LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR
     */
    protected const LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR = 'export_root_dir';

    protected const DESTINATION_TEMPLATE =
        'merchants/{merchant_name}/merchant-orders/{data_entity}s_{store_name}_{timestamp}.{extension}';
    protected const FORMATTER_TYPE = 'csv';
    protected const CONNECTION_TYPE = 'local';
    protected const EXTENSION = 'csv';

    protected const DATA_ENTITY_MERCHANT_ORDER = 'merchant-order';
    protected const DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_MERCHANT_ORDER = 'merchant-orders_DE_%s.csv';

    protected const DATA_ENTITY_MERCHANT_ORDER_ITEM = 'merchant-order-item';
    protected const DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_MERCHANT_ORDER_ITEM = 'merchant-order-items_DE_%s.csv';

    protected const DATA_ENTITY_MERCHANT_ORDER_EXPENSE = 'merchant-order-expense';
    protected const DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_MERCHANT_ORDER_EXPENSE = 'merchant-order-expenses_DE_%s.csv';

    protected const EXPORT_ROOT_DIR = '{application_root_dir}/data/export';

    /**
     * @var \SprykerTest\Zed\MerchantSalesOrderDataExport\MerchantSalesOrderDataExportBusinessTester
     */
    protected $tester;

    /**
     * @var int
     */
    protected $timestamp;

    /**
     * @var string
     */
    protected $merchantName;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $merchantTransfer = $this->tester->haveMerchant();
        $this->merchantName = $merchantTransfer->getName();

        $salesOrder = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        $salesOrderItem = $this->tester->createSalesOrderItemForOrder($salesOrder->getIdSalesOrder());
        $this->tester->createOrderExpense($salesOrder->getIdSalesOrder(), $merchantTransfer->getMerchantReference());

        $merchantOrderTransfer = $this->tester->haveMerchantOrder([
            MerchantOrderTransfer::ID_ORDER => $salesOrder->getIdSalesOrder(),
            MerchantOrderTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
        ]);
        $merchantOrderItemTransfer = $this->tester->haveMerchantOrderItem([
            MerchantOrderItemTransfer::ID_ORDER_ITEM => $salesOrderItem->getIdSalesOrderItem(),
            MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
        ]);

        $this->timestamp = time();
    }

    /**
     * @return void
     */
    public function testMerchantOrderExportWillReturnReportTransferWithCorrectData(): void
    {
        //Arrange
        $dataExportConfigurationTransfer = $this->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER
        );

        //Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrder($dataExportConfigurationTransfer);

        //Assert
        $this->assertDataExport(
            $dataExportReportTransfer,
            static::DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_MERCHANT_ORDER
        );
    }

    /**
     * @return void
     */
    public function testMerchantOrderItemExportWillReturnReportTransferWithCorrectData(): void
    {
        //Arrange
        $dataExportConfigurationTransfer = $this->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER_ITEM
        );

        //Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrderItem($dataExportConfigurationTransfer);

        //Assert
        $this->assertDataExport(
            $dataExportReportTransfer,
            static::DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_MERCHANT_ORDER_ITEM
        );
    }

    /**
     * @return void
     */
    public function testMerchantOrderExpenseExportWillReturnReportTransferWithCorrectData(): void
    {
        //Arrange
        $dataExportConfigurationTransfer = $this->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER_EXPENSE
        );

        //Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrderExpense($dataExportConfigurationTransfer);

        //Assert
        $this->assertDataExport(
            $dataExportReportTransfer,
            static::DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_MERCHANT_ORDER_EXPENSE
        );
    }

    /**
     * @param string $dataEntity
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    protected function createDataExportConfigurationTransfer(string $dataEntity): DataExportConfigurationTransfer
    {
        $dataExportFormatConfigurationTransfer = (new DataExportFormatConfigurationTransfer())
            ->setType(static::FORMATTER_TYPE);
        $dataExportConnectionConfigurationTransfer = (new DataExportConnectionConfigurationTransfer())
            ->setType(static::CONNECTION_TYPE)
            ->setParams([
                static::LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR => '{application_root_dir}/data/export',
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
                'timestamp' => $this->timestamp,
                'extension' => static::EXTENSION,
            ]);
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportReportTransfer $dataExportReportTransfer
     * @param string $dataExportResultFileTemplate
     *
     * @return void
     */
    protected function assertDataExport(
        DataExportReportTransfer $dataExportReportTransfer,
        string $dataExportResultFileTemplate
    ): void {
        $this->assertTrue($dataExportReportTransfer->getIsSuccessful());
        $this->assertCount(
            1,
            $dataExportReportTransfer->getDataExportResults(),
            'Number of result transfers does not equals to an expected value.'
        );

        /** @var \Generated\Shared\Transfer\DataExportResultTransfer $dataExportResultTransfer */
        $dataExportResultTransfer = $dataExportReportTransfer->getDataExportResults()->offsetGet(0);
        $exportCount = $dataExportResultTransfer->getExportCount();
        $this->assertSame(
            1,
            $dataExportResultTransfer->getExportCount(),
            'Export count does not equals to an expected value.'
        );

        $fileName = $dataExportResultTransfer->getFileName();
        $this->assertSame(
            sprintf($dataExportResultFileTemplate, $this->timestamp),
            $fileName,
            'File name does not equals to an expected value'
        );

        $filePath = APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'data/export' .
            DIRECTORY_SEPARATOR . 'merchants' . DIRECTORY_SEPARATOR . $this->merchantName .
            DIRECTORY_SEPARATOR . 'merchant-orders' . DIRECTORY_SEPARATOR . $fileName;
        $this->assertFileExists($filePath);

        $this->tester->removeGeneratedFile($filePath);
    }
}
