<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesDataExport\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportConnectionConfigurationTransfer;
use Generated\Shared\Transfer\DataExportFormatConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesDataExport
 * @group Business
 * @group Facade
 * @group SalesDataExportFacadeTest
 * Add your own group annotations below this line
 */
class SalesDataExportFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Service\DataExport\Writer\DataExportLocalWriter::LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR
     */
    protected const LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR = 'export_root_dir';

    protected const DESTINATION_TEMPLATE = '{data_entity}s_DE_{timestamp}.{extension}';
    protected const FORMATTER_TYPE = 'csv';
    protected const CONNECTION_TYPE = 'local';
    protected const EXTENSION = 'csv';

    protected const STORE_DE = 'DE';

    protected const DATA_ENTITY_ORDER = 'order';
    protected const DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_ORDER = 'orders_DE_%s.csv';

    protected const DATA_ENTITY_ORDER_ITEM = 'order-item';
    protected const DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_ORDER_ITEM = 'order-items_DE_%s.csv';

    protected const DATA_ENTITY_ORDER_EXPENSE = 'order-expense';
    protected const DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_ORDER_EXPENSE = 'order-expenses_DE_%s.csv';

    /**
     * @var \SprykerTest\Zed\SalesDataExport\SalesDataExportBusinessTester
     */
    protected $tester;

    /**
     * @var int
     */
    protected $timestamp;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $saveOrderTransfer = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveOrderExpense($saveOrderTransfer->getIdSalesOrder());

        $this->timestamp = time();
    }

    /**
     * @return void
     */
    public function testOrderExportWillReturnReportTransferWithCorrectData(): void
    {
        //Arrange
        $dataExportConfigurationTransfer = $this->createDataExportConfigurationTransfer(static::DATA_ENTITY_ORDER);

        //Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportOrder($dataExportConfigurationTransfer);

        //Assert
        $this->assertDataExport($dataExportReportTransfer, static::DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_ORDER);
    }

    /**
     * @return void
     */
    public function testOrderItemExportWillReturnReportTransferWithCorrectData(): void
    {
        //Arrange
        $dataExportConfigurationTransfer = $this->createDataExportConfigurationTransfer(static::DATA_ENTITY_ORDER_ITEM);

        //Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportOrderItem($dataExportConfigurationTransfer);

        //Assert
        $this->assertDataExport($dataExportReportTransfer, static::DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_ORDER_ITEM);
    }

    /**
     * @return void
     */
    public function testOrderExpenseExportWillReturnReportTransferWithCorrectData(): void
    {
        //Arrange
        $dataExportConfigurationTransfer = $this->createDataExportConfigurationTransfer(static::DATA_ENTITY_ORDER_EXPENSE);

        //Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportOrderExpense($dataExportConfigurationTransfer);

        //Assert
        $this->assertDataExport($dataExportReportTransfer, static::DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_ORDER_EXPENSE);
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
                'order_created_at' => [
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
    protected function assertDataExport(DataExportReportTransfer $dataExportReportTransfer, string $dataExportResultFileTemplate): void
    {
        $this->assertTrue($dataExportReportTransfer->getIsSuccessful());
        $this->assertCount(1, $dataExportReportTransfer->getDataExportResults(), 'Number of result transfers does not equals to an expected value.');

        /** @var \Generated\Shared\Transfer\DataExportResultTransfer $dataExportResultTransfer */
        $dataExportResultTransfer = $dataExportReportTransfer->getDataExportResults()->offsetGet(0);
        $this->assertEquals(1, $dataExportResultTransfer->getExportCount(), 'Export count does not equals to an expected value.');

        $fileName = $dataExportResultTransfer->getFileName();
        $this->assertEquals(
            sprintf($dataExportResultFileTemplate, $this->timestamp),
            $fileName,
            'File name does not equals to an expected value'
        );

        $filePath = APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'data/export' . DIRECTORY_SEPARATOR . $fileName;
        $this->assertFileExists($filePath);

        $this->tester->removeGeneratedFile($filePath);
    }
}
