<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrderDataExport\Business;

use Codeception\Test\Unit;
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
    protected const DATA_ENTITY_MERCHANT_ORDER = 'merchant-order';
    protected const DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_MERCHANT_ORDER = 'merchant-orders_DE_%s.csv';

    protected const DATA_ENTITY_MERCHANT_ORDER_ITEM = 'merchant-order-item';
    protected const DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_MERCHANT_ORDER_ITEM = 'merchant-order-items_DE_%s.csv';

    protected const DATA_ENTITY_MERCHANT_ORDER_EXPENSE = 'merchant-order-expense';
    protected const DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_MERCHANT_ORDER_EXPENSE = 'merchant-order-expenses_DE_%s.csv';

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
    protected $merchantTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $this->merchantTransfer = $this->tester->haveMerchant();
        $this->timestamp = time();
    }

    /**
     * @return void
     */
    public function testExportMerchantOrderReturnsResultTransferWithCorrectData(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->tester->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER,
            $this->timestamp
        );
        $this->tester->createMerchantOrder($this->merchantTransfer->getMerchantReference());

        // Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrder($dataExportConfigurationTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\DataExportResultTransfer $dataExportResultTransfer */
        $dataExportResultTransfer = $dataExportReportTransfer->getDataExportResults()->offsetGet(0);

        $this->assertSame(
            1,
            $dataExportResultTransfer->getExportCount(),
            'Exported rows count does not equals to an expected value.'
        );
    }

    /**
     * @return void
     */
    public function testExportMerchantOrderReturnsTheCorrectQuantityOfResultTransfers(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->tester->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER,
            $this->timestamp
        );
        $this->tester->createMerchantOrder($this->merchantTransfer->getMerchantReference());

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
    public function testExportMerchantOrderCreatesExportFileWithCorrectNameAndFilePath(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->tester->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER,
            $this->timestamp
        );
        $this->tester->createMerchantOrder($this->merchantTransfer->getMerchantReference());

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

        $exportFilePath = APPLICATION_ROOT_DIR . 'data/export' .
            DIRECTORY_SEPARATOR . 'merchants' . DIRECTORY_SEPARATOR . $this->merchantTransfer->getName() .
            DIRECTORY_SEPARATOR . 'merchant-orders' . DIRECTORY_SEPARATOR . $dataExportResultTransfer->getFileName();

        $this->assertFileExists($exportFilePath);

        unlink($exportFilePath);
    }

   /**
    * @return void
    */
    public function testExportMerchantOrderReturnsUnsuccessfulDataExportReportTransferInCaseOfEmptyData(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->tester->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER,
            $this->timestamp
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
     * @return void
     */
    public function testExportMerchantOrderItemReturnsResultTransferWithCorrectData(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->tester->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER_ITEM,
            $this->timestamp
        );
        $this->tester->createMerchantOrderItem($this->merchantTransfer->getMerchantReference());

        // Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrderItem($dataExportConfigurationTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\DataExportResultTransfer $dataExportResultTransfer */
        $dataExportResultTransfer = $dataExportReportTransfer->getDataExportResults()->offsetGet(0);

        $this->assertSame(
            1,
            $dataExportResultTransfer->getExportCount(),
            'Exported rows count does not equals to an expected value.'
        );
    }

    /**
     * @return void
     */
    public function testExportMerchantOrderItemReturnsTheCorrectQuantityOfResultTransfers(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->tester->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER_ITEM,
            $this->timestamp
        );
        $this->tester->createMerchantOrderItem($this->merchantTransfer->getMerchantReference());

        // Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrderItem($dataExportConfigurationTransfer);

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
    public function testExportMerchantOrderItemCreatesExportFileWithCorrectNameAndFilePath(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->tester->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER_ITEM,
            $this->timestamp
        );
        $this->tester->createMerchantOrderItem($this->merchantTransfer->getMerchantReference());

        // Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrderItem($dataExportConfigurationTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\DataExportResultTransfer $dataExportResultTransfer */
        $dataExportResultTransfer = $dataExportReportTransfer->getDataExportResults()->offsetGet(0);

        $this->assertSame(
            sprintf(static::DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_MERCHANT_ORDER_ITEM, $this->timestamp),
            $dataExportResultTransfer->getFileName(),
            'File name does not equals to an expected value'
        );

        $exportFilePath = APPLICATION_ROOT_DIR . 'data/export' .
            DIRECTORY_SEPARATOR . 'merchants' . DIRECTORY_SEPARATOR . $this->merchantTransfer->getName() .
            DIRECTORY_SEPARATOR . 'merchant-orders' . DIRECTORY_SEPARATOR . $dataExportResultTransfer->getFileName();

        $this->assertFileExists($exportFilePath);

        unlink($exportFilePath);
    }

    /**
     * @return void
     */
    public function testExportMerchantOrderItemReturnsUnsuccessfulDataExportReportTransferInCaseOfEmptyData(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->tester->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER_ITEM,
            $this->timestamp
        );

        // Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrderItem($dataExportConfigurationTransfer);

        // Assert
        $this->assertFalse(
            $dataExportReportTransfer->getIsSuccessful(),
            'The data export is successful despite the empty data.'
        );
    }

    /**
     * @return void
     */
    public function testExportMerchantOrderExpenseReturnsResultTransferWithCorrectData(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->tester->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER_EXPENSE,
            $this->timestamp
        );
        $this->tester->createMerchantOrderExpense($this->merchantTransfer->getMerchantReference());

        // Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrderExpense($dataExportConfigurationTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\DataExportResultTransfer $dataExportResultTransfer */
        $dataExportResultTransfer = $dataExportReportTransfer->getDataExportResults()->offsetGet(0);

        $this->assertSame(
            1,
            $dataExportResultTransfer->getExportCount(),
            'Exported rows count does not equals to an expected value.'
        );
    }

    /**
     * @return void
     */
    public function testExportMerchantOrderExpenseReturnsTheCorrectQuantityOfResultTransfers(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->tester->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER_EXPENSE,
            $this->timestamp
        );
        $this->tester->createMerchantOrderExpense($this->merchantTransfer->getMerchantReference());

        // Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrderExpense($dataExportConfigurationTransfer);

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
    public function testExportMerchantOrderExpenseCreatesExportFileWithCorrectNameAndFilePath(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->tester->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER_EXPENSE,
            $this->timestamp
        );
        $this->tester->createMerchantOrderExpense($this->merchantTransfer->getMerchantReference());

        // Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrderExpense($dataExportConfigurationTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\DataExportResultTransfer $dataExportResultTransfer */
        $dataExportResultTransfer = $dataExportReportTransfer->getDataExportResults()->offsetGet(0);

        $this->assertSame(
            sprintf(static::DATA_EXPORT_RESULT_FILE_NAME_TEMPLATE_MERCHANT_ORDER_EXPENSE, $this->timestamp),
            $dataExportResultTransfer->getFileName(),
            'File name does not equals to an expected value'
        );

        $exportFilePath = APPLICATION_ROOT_DIR . 'data/export' .
            DIRECTORY_SEPARATOR . 'merchants' . DIRECTORY_SEPARATOR . $this->merchantTransfer->getName() .
            DIRECTORY_SEPARATOR . 'merchant-orders' . DIRECTORY_SEPARATOR . $dataExportResultTransfer->getFileName();

        $this->assertFileExists($exportFilePath);

        unlink($exportFilePath);
    }

    /**
     * @return void
     */
    public function testExportMerchantOrderExpenseReturnsUnsuccessfulDataExportReportTransferInCaseOfEmptyData(): void
    {
        // Arrange
        $dataExportConfigurationTransfer = $this->tester->createDataExportConfigurationTransfer(
            static::DATA_ENTITY_MERCHANT_ORDER_EXPENSE,
            $this->timestamp
        );

        // Act
        $dataExportReportTransfer = $this->tester->getFacade()->exportMerchantOrderExpense($dataExportConfigurationTransfer);

        // Assert
        $this->assertFalse(
            $dataExportReportTransfer->getIsSuccessful(),
            'The data export is successful despite the empty data.'
        );
    }
}
