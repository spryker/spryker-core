<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Service\DataExport\Service\Formatter;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportFormatConfigurationTransfer;
use Spryker\Service\DataExport\Dependency\External\DataExportToCsvFormatterInterface;
use Spryker\Service\DataExport\Formatter\DataExportCsvFormatter;

/**
 * Auto-generated group annotations
 *
 * @group Service
 * @group DataExport
 * @group Service
 * @group Formatter
 * @group DataExportCsvFormatterTest
 * Add your own group annotations below this line
 */
class DataExportCsvFormatterTest extends Unit
{
    protected const FORMAT_TYPE_CSV = 'csv';

    protected const CSV_DATA = [
        ['column_1' => 'data_1_1', 'column_2' => 'data_1_2'],
        ['column_1' => 'data_2_1', 'column_2' => 'data_2_2'],
    ];

    /**
     * @return void
     */
    public function testFormatBatchWillFormatCsvDataArrayToCsvString(): void
    {
        //Arrange
        $dataExportConfigurationTransfer = $this->createDataExportConfigurationTransfer();

        //Act
        $dataExportCsvFormatter = new DataExportCsvFormatter($this->mockCsvFormatter());
        $dataExportFormatResponseTransfer = $dataExportCsvFormatter->formatBatch(
            (new DataExportBatchTransfer())
                ->setOffset(0)
                ->setData(static::CSV_DATA)
                ->setFields(array_keys(static::CSV_DATA)),
            $dataExportConfigurationTransfer
        );

        //Arrange
        $this->assertTrue($dataExportFormatResponseTransfer->getIsSuccessful());
        $csvString = $dataExportFormatResponseTransfer->getDataFormatted();
        $this->assertEquals(
            $this->getExpectedCsvString(static::CSV_DATA),
            $csvString,
            'Formatted csv data does not equals to an expected csv string.'
        );
    }

    /**
     * @return void
     */
    public function testFormatBatchWillReturnNotSuccessfulResponseInCaseOfInvalidData(): void
    {
        //Arrange
        $dataExportConfigurationTransfer = $this->createDataExportConfigurationTransfer();
        $invalidData = ['invalid data array'];

        //Act
        $dataExportCsvFormatter = new DataExportCsvFormatter($this->mockCsvFormatter());
        $dataExportFormatResponseTransfer = $dataExportCsvFormatter->formatBatch(
            (new DataExportBatchTransfer())
                ->setOffset(0)
                ->setData($invalidData)
                ->setFields([]),
            $dataExportConfigurationTransfer
        );

        //Arrange
        $this->assertFalse($dataExportFormatResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testGetFormatExtensionWillReturnExtensionCsv(): void
    {
        //Arrange
        $dataExportConfigurationTransfer = $this->createDataExportConfigurationTransfer();

        //Act
        $dataExportCsvFormatter = new DataExportCsvFormatter($this->mockCsvFormatter());
        $extension = $dataExportCsvFormatter->getFormatExtension($dataExportConfigurationTransfer);

        //Assert
        $this->assertEquals('csv', $extension, 'Expected extension is "csv"');
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\DataExport\Dependency\External\DataExportToCsvFormatterInterface
     */
    protected function mockCsvFormatter(): DataExportToCsvFormatterInterface
    {
        $csvFormatterMock = $this->getMockBuilder(DataExportToCsvFormatterInterface::class)->getMock();
        $csvFormatterMock->method('addRecord')->willReturn(1);
        $csvFormatterMock->method('getFormattedRecords')->willReturn($this->getExpectedCsvString(static::CSV_DATA));

        return $csvFormatterMock;
    }

    /**
     * @param array $csvData
     *
     * @return string
     */
    protected function getExpectedCsvString(array $csvData): string
    {
        $formattedRows = [];
        foreach ($csvData as $csvRow) {
            $formattedRows[] = implode(',', $csvRow);
        }

        return implode(PHP_EOL, $formattedRows);
    }

    /**
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    protected function createDataExportConfigurationTransfer(): DataExportConfigurationTransfer
    {
        $dataExportFormatConfigurationTransfer = (new DataExportFormatConfigurationTransfer())->setType(static::FORMAT_TYPE_CSV);

        return (new DataExportConfigurationTransfer())->setFormat($dataExportFormatConfigurationTransfer);
    }
}
