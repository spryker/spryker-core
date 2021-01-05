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
     * @var string|null
     */
    protected $merchantReference;

    /**
     * @var string
     */
    protected $exportFilePath;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $merchantTransfer = $this->tester->haveMerchant();
        $this->merchantReference = $merchantTransfer->getMerchantReference();
        $this->merchantName = $merchantTransfer->getName();
        $this->timestamp = time();
    }

    /**
     * @return void
     */
    public function testExportMerchantOrderWillReturnResultTransferWithCorrectData(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER
        );
        $salesOrder = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveMerchantOrder([
            MerchantOrderTransfer::ID_ORDER => $salesOrder->getIdSalesOrder(),
            MerchantOrderTransfer::MERCHANT_REFERENCE => $this->merchantReference,
        ]);

        // Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrder($dataExportConfigurationTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\DataExportResultTransfer $dataExportResultTransfer */
        $dataExportResultTransfer = $dataExportReportTransfer->getDataExportResults()->offsetGet(0);
        $exportCount = $dataExportResultTransfer->getExportCount();
        $this->assertSame(
            1,
            $dataExportResultTransfer->getExportCount(),
            'Exported rows count does not equals to an expected value.'
        );
    }

    /**
     * @return void
     */
    public function testExportMerchantOrderWillReturnTheCorrectQuantityOfResultTransfers(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER
        );
        $salesOrder = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveMerchantOrder([
            MerchantOrderTransfer::ID_ORDER => $salesOrder->getIdSalesOrder(),
            MerchantOrderTransfer::MERCHANT_REFERENCE => $this->merchantReference,
        ]);

        // Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrder($dataExportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataExportReportTransfer->getIsSuccessful());
        $this->assertCount(
            1,
            $dataExportReportTransfer->getDataExportResults(),
            'Number of result transfers does not equals to an expected value.'
        );
    }

    /**
     * @return void
     */
    public function testExportMerchantOrderWillCreateExportFileWithCorrectNameAndFilePath(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER
        );
        $salesOrder = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveMerchantOrder([
            MerchantOrderTransfer::ID_ORDER => $salesOrder->getIdSalesOrder(),
            MerchantOrderTransfer::MERCHANT_REFERENCE => $this->merchantReference,
        ]);

        // Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrder($dataExportConfigurationTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\DataExportResultTransfer $dataExportResultTransfer */
        $dataExportResultTransfer = $dataExportReportTransfer->getDataExportResults()->offsetGet(0);

        $this->assertSame(
            sprintf(static::DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_MERCHANT_ORDER, $this->timestamp),
            $dataExportResultTransfer->getFileName(),
            'File name does not equals to an expected value'
        );

        $this->exportFilePath = APPLICATION_ROOT_DIR . 'data/export' .
            DIRECTORY_SEPARATOR . 'merchants' . DIRECTORY_SEPARATOR . $this->merchantName .
            DIRECTORY_SEPARATOR . 'merchant-orders' . DIRECTORY_SEPARATOR . $dataExportResultTransfer->getFileName();

        $this->assertFileExists($this->exportFilePath);

        $this->tester->removeGeneratedFile($this->exportFilePath);
    }

   /**
    * @return void
    */
    public function testExportMerchantOrderWillReturnUnsuccessfulDataExportReportTransferInCaseOfEmptyData(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER
        );

        // Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrder($dataExportConfigurationTransfer);

        // Assert
        $this->assertFalse(
            $dataExportReportTransfer->getIsSuccessful(),
            'The data export is successful despite the empty data.'
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
}
