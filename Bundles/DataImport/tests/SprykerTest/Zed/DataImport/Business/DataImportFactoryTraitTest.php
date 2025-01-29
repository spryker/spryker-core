<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterDataSourceConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use ReflectionClass;
use Spryker\Zed\DataImport\Business\DataImportFactoryTrait;
use Spryker\Zed\DataImport\Business\Model\DataImporter;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group DataImportFactoryTraitTest
 * Add your own group annotations below this line
 */
class DataImportFactoryTraitTest extends Unit
{
    use DataImportFactoryTrait;

    /**
     * @var \SprykerTest\Zed\DataImport\DataImportBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider dataProvider
     *
     * @param string|null $directory
     * @param string $expectedDirectory
     *
     * @return void
     */
    public function testBuildImporterConfigurationWithDirectorySet(?string $directory, string $expectedDirectory): void
    {
        // Arrange
        $dataImporterDataSourceConfigurationTransfer = new DataImporterDataSourceConfigurationTransfer();
        $dataImporterDataSourceConfigurationTransfer->setDirectory($directory);
        $dataImporterDataSourceConfigurationTransfer->setFileName('test.csv');
        $dataImporterDataSourceConfigurationTransfer->setImportType('some-entity');
        $dataImporterDataSourceConfigurationTransfer->setModuleName('TestModule');

        // Use reflection to access the private method
        $reflection = new ReflectionClass($this);
        $method = $reflection->getMethod('buildImporterConfiguration');
        $method->setAccessible(true);

        // Act
        $dataImporterConfigurationTransfer = $method->invoke($this, $dataImporterDataSourceConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterConfigurationTransfer::class, $dataImporterConfigurationTransfer);

        $readerConfiguration = $dataImporterConfigurationTransfer->getReaderConfiguration();
        $this->assertInstanceOf(DataImporterReaderConfigurationTransfer::class, $readerConfiguration);

        $dataImportDirectory = $readerConfiguration->getDirectories()[0];
        $fullFileName = $readerConfiguration->getFileName();

        $this->assertSame($expectedDirectory, $dataImportDirectory);

        $expectedFullFileName = realpath(
            dirname($reflection->getFileName())
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..',
        ) . '/TestModule/data/import/test.csv';

        $this->assertSame($expectedFullFileName, $fullFileName);
        $this->assertSame('some-entity', $dataImporterConfigurationTransfer->getImportType());
    }

    /**
     * @return void
     */
    public function testBuildImporterConfigurationWithRealModule(): void
    {
        // Arrange
        $dataImporterDataSourceConfigurationTransfer = new DataImporterDataSourceConfigurationTransfer();
        $dataImporterDataSourceConfigurationTransfer->setFileName('product_label.csv');
        $dataImporterDataSourceConfigurationTransfer->setImportType('product-label');
        $dataImporterDataSourceConfigurationTransfer->setModuleName('ProductLabelDataImport');

        // Use reflection to access the private method
        $reflection = new ReflectionClass($this);
        $method = $reflection->getMethod('buildImporterConfiguration');
        $method->setAccessible(true);

        // Act
        $dataImporterConfigurationTransfer = $method->invoke($this, $dataImporterDataSourceConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterConfigurationTransfer::class, $dataImporterConfigurationTransfer);

        $readerConfiguration = $dataImporterConfigurationTransfer->getReaderConfiguration();
        $this->assertInstanceOf(DataImporterReaderConfigurationTransfer::class, $readerConfiguration);

        $fullFileName = $readerConfiguration->getFileName();

        $expectedFullFileName = realpath(
            dirname($reflection->getFileName())
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..',
        ) . '/ProductLabelDataImport/data/import/product_label.csv';

        $this->assertSame($expectedFullFileName, $fullFileName);
        $this->assertSame('product-label', $dataImporterConfigurationTransfer->getImportType());
    }

    /**
     * @return void
     */
    public function testGetCsvDataImporterFromConfigOnExistingModule(): void
    {
        // Arrange
        $dataImporterDataSourceConfigurationTransfer = new DataImporterDataSourceConfigurationTransfer();
        $dataImporterDataSourceConfigurationTransfer->setFileName('product_label.csv');
        $dataImporterDataSourceConfigurationTransfer->setImportType('product-label');
        $dataImporterDataSourceConfigurationTransfer->setModuleName('ProductLabelDataImport');

        // Act
        $dataImporter = $this->getCsvDataImporterFromConfig($dataImporterDataSourceConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporter::class, $dataImporter);
        $this->assertSame('product-label', $dataImporter->getImportType());
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function dataProvider(): array
    {
        return [
            'check when directory is not set' => [
                'directory' => null,
                'expectedDirectory' => APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR,
            ],
            'check when directory is set' => [
                'directory' => '/data/import/',
                'expectedDirectory' => '/data/import/',
            ],
        ];
    }
}
