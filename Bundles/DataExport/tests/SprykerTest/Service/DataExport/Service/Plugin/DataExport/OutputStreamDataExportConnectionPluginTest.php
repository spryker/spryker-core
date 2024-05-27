<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\DataExport\Service\Plugin\DataExport;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\DataExportBatchBuilder;
use Generated\Shared\DataBuilder\DataExportConfigurationBuilder;
use Generated\Shared\DataBuilder\DataExportFormatResponseBuilder;
use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportConnectionConfigurationTransfer;
use Generated\Shared\Transfer\DataExportFormatResponseTransfer;
use Spryker\Service\DataExport\Plugin\DataExport\OutputStreamDataExportConnectionPlugin;
use SprykerTest\Service\DataExport\DataExportServiceTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group DataExport
 * @group Service
 * @group Plugin
 * @group DataExport
 * @group OutputStreamDataExportConnectionPluginTest
 * Add your own group annotations below this line
 */
class OutputStreamDataExportConnectionPluginTest extends Unit
{
    /**
     * @uses \Spryker\Service\DataExport\Plugin\DataExport\OutputStreamDataExportConnectionPlugin::CONNECTION_TYPE_OUTPUT_STREAM
     *
     * @var string
     */
    protected const CONNECTION_TYPE_OUTPUT_STREAM = 'output-stream';

    /**
     * @uses \Spryker\Service\DataExport\Writer\OutputStreamFormattedDataExportWriter::ERROR_MESSAGE_WRITE_FAIL
     *
     * @var string
     */
    protected const ERROR_MESSAGE_WRITE_FAIL = 'Failed to write to stream';

    /**
     * @var string
     */
    protected const TEST_DATA_FORMATTED = 'header 1,header 2,header 3\nrow1_1,row1_2,row1_3\nrow2_1,row2_2,row2_3\n';

    /**
     * @var \SprykerTest\Service\DataExport\DataExportServiceTester
     */
    protected DataExportServiceTester $tester;

    /**
     * @dataProvider isApplicableReturnsExpectedValueAccordingToProvidedConnectionTypeDataProvider
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testIsApplicableReturnsExpectedValueAccordingToProvidedConnectionType(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer,
        bool $expectedResult
    ): void {
        // Arrange
        $outputStreamDataExportConnectionPlugin = new OutputStreamDataExportConnectionPlugin();

        // Act
        $isApplicable = $outputStreamDataExportConnectionPlugin->isApplicable($dataExportConfigurationTransfer);

        // Assert
        $this->assertSame($expectedResult, $isApplicable);
    }

    /**
     * @return void
     */
    public function testWriteWritesDataToProvidedOutputStream(): void
    {
        // Arrange
        $dataExportFormatResponseTransfer = (new DataExportFormatResponseBuilder([
            DataExportFormatResponseTransfer::DATA_FORMATTED => static::TEST_DATA_FORMATTED,
        ]))->build();
        $dataExportBatchTransfer = (new DataExportBatchBuilder([DataExportBatchTransfer::OFFSET => 0]))->build();
        $dataExportConfigurationTransfer = (new DataExportConfigurationBuilder([
            DataExportConfigurationTransfer::DESTINATION => 'php://output',
        ]))->build();

        $outputStreamDataExportConnectionPlugin = new OutputStreamDataExportConnectionPlugin();

        // Act
        ob_start();
        $dataExportWriteResponseTransfer = $outputStreamDataExportConnectionPlugin->write(
            $dataExportFormatResponseTransfer,
            $dataExportBatchTransfer,
            $dataExportConfigurationTransfer,
        );
        $output = ob_get_clean();

        // Assert
        $this->assertTrue($dataExportWriteResponseTransfer->getIsSuccessful());
        $this->assertCount(0, $dataExportWriteResponseTransfer->getMessages());
        $this->assertSame(static::TEST_DATA_FORMATTED, $output);
    }

    /**
     * @return void
     */
    public function testWriteReturnsErrorWhenProvidedDestinationIsNotWritableStream(): void
    {
        // Arrange
        $dataExportFormatResponseTransfer = (new DataExportFormatResponseBuilder([
            DataExportFormatResponseTransfer::DATA_FORMATTED => static::TEST_DATA_FORMATTED,
        ]))->build();
        $dataExportBatchTransfer = (new DataExportBatchBuilder([DataExportBatchTransfer::OFFSET => 0]))->build();
        $dataExportConfigurationTransfer = (new DataExportConfigurationBuilder([
            DataExportConfigurationTransfer::DESTINATION => 'php://input',
        ]))->build();

        $outputStreamDataExportConnectionPlugin = new OutputStreamDataExportConnectionPlugin();

        // Act
        $dataExportWriteResponseTransfer = $outputStreamDataExportConnectionPlugin->write(
            $dataExportFormatResponseTransfer,
            $dataExportBatchTransfer,
            $dataExportConfigurationTransfer,
        );

        // Assert
        $this->assertFalse($dataExportWriteResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $dataExportWriteResponseTransfer->getMessages());
        $this->assertStringContainsString(
            static::ERROR_MESSAGE_WRITE_FAIL,
            $dataExportWriteResponseTransfer->getMessages()->getIterator()->current()->getValue(),
        );
    }

    /**
     * @return array<string, list<mixed>>
     */
    protected function isApplicableReturnsExpectedValueAccordingToProvidedConnectionTypeDataProvider(): array
    {
        return [
            'Connection type is output stream' => [
                (new DataExportConfigurationBuilder())->withConnection([
                    DataExportConnectionConfigurationTransfer::TYPE => static::CONNECTION_TYPE_OUTPUT_STREAM,
                ])->build(),
                true,
            ],
            'Connection type is not output stream' => [
                (new DataExportConfigurationBuilder())->withConnection([
                    DataExportConnectionConfigurationTransfer::TYPE => 'not-output-stream',
                ])->build(),
                false,
            ],
        ];
    }
}
