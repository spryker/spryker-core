<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataExport\Business\Exporter;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataExportBatchTransfer;
use Spryker\Zed\DataExport\Business\DataEntityPluginProvider\DataExportPluginProvider;
use Spryker\Zed\DataExport\Business\Exception\DataExporterNotFoundException;
use Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityReaderPluginInterface;
use SprykerTest\Zed\DataExport\DataExportBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataExport
 * @group Business
 * @group Exporter
 * @group DataExportExecutorTest
 * Add your own group annotations below this line
 */
class DataExportExecutorTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DataExport\DataExportBusinessTester
     */
    protected DataExportBusinessTester $tester;

    /**
     * @param array $testData
     * @param array $fields
     *
     * @return string
     */
    public function exportDataFromExecutorAndReturnFileContants(array $testData, array $fields = ['id', 'name']): string
    {
        $dataExportExecutor = $this->tester->getDataExportExecutor(
            $this->mockDataExportPluginProvider($testData),
        );

        $resultTransfer = $dataExportExecutor->exportDataEntities(
            $this->tester->getDataExportConfigurationsTransferWithFields($fields),
        );

        $filePath = '/data/data/export/' . $resultTransfer[0]->getDataExportResults()->offsetGet(0)->getFileName();

        $contents = file_get_contents($filePath);

        unlink($filePath);

        return $contents;
    }

    /**
     * @return void
     */
    public function testExportCreatesFileWithCorrectContents(): void
    {
        // Arrange
        $testData = [
            ['id' => 1, 'name' => 'test1'],
            ['id' => 2, 'name' => 'test2'],
        ];

        // Act
        $contents = $this->exportDataFromExecutorAndReturnFileContants($testData);

        // Assert
        foreach ($testData as $datum) {
            $this->assertStringContainsString($datum['name'], $contents);
        }
    }

    /**
     * @return void
     */
    public function testExportThrowsExceptionWhenDataEntityPluginDoesNotExist()
    {
        $this->expectException(DataExporterNotFoundException::class);

        $dataExportExecutor = $this->tester->getDataExportExecutor(new DataExportPluginProvider([], [], []));

        // Act
        $dataExportExecutor->exportDataEntities($this->tester->getDataExportConfigurationsTransferWithoutFields());
    }

    /**
     * @return void
     */
    public function testExportCreatesEmptyFileIfNoDataIsFound(): void
    {
        // Arrange
        $testData = [];

        // Act
        $contents = $this->exportDataFromExecutorAndReturnFileContants($testData);

        // Assert
        $this->assertEquals($contents, "id,name\n");
    }

    /**
     * @return void
     */
    public function testExportMapsDataCorrectly(): void
    {
        // Arrange
        $testData = [
            ['id' => 1, 'name' => 'test1'],
            ['id' => 2, 'name' => 'test2'],
        ];

        $fields = ['theId:id', 'theName:name'];

        // Act
        $contents = $this->exportDataFromExecutorAndReturnFileContants($testData, $fields);

        // Assert
        $this->assertEquals("theId,theName\n1,test1\n2,test2\n", $contents);
    }

    /**
     * @return void
     */
    public function testExportMapsNestedDataCorrectly(): void
    {
        // Arrange
        $testData = [
            [
                'id' => [
                    ['nestedId' => 1],
                ],
                'name' => [
                    ['nestedName' => 'test1'],
                ],
            ],
        ];

        $fields = [
            'theId.*.theNesteId:id.*.nestedId',
            'theName.*.theNestedName:name.*.nestedName',
        ];

        // Act
        $contents = $this->exportDataFromExecutorAndReturnFileContants($testData, $fields);

        // Assert
        $this->assertEquals("theId.*.theNesteId,theName.*.theNestedName\n1,test1\n", $contents);
    }

    /**
     * @param array $testData
     *
     * @return \Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityReaderPluginInterface
     */
    public function mockDataEntityReaderPlugin(array $testData): DataEntityReaderPluginInterface
    {
        $dataEntityReaderPluginMock = $this->createMock(DataEntityReaderPluginInterface::class);

        $dataEntityReaderPluginMock->method('getDataBatch')->willReturn(
            (new DataExportBatchTransfer())->setData($testData),
        );

        return $dataEntityReaderPluginMock;
    }

    /**
     * @param array $testData
     *
     * @return \Spryker\Zed\DataExport\Business\DataEntityPluginProvider\DataExportPluginProvider
     */
    public function mockDataExportPluginProvider(array $testData): DataExportPluginProvider
    {
        $dataExportPluginProviderMock = $this->createMock(
            DataExportPluginProvider::class,
        );

        $dataExportPluginProviderMock->method('hasDataEntityPlugin')->willReturn(false);
        $dataExportPluginProviderMock->method('getDataEntityPluginForInterface')->willReturn($this->mockDataEntityReaderPlugin($testData));

        return $dataExportPluginProviderMock;
    }
}
