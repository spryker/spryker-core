<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\StockAddressDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\StockAddressDataImport\Communication\Plugin\DataImport\StockAddressDataImportPlugin;
use Spryker\Zed\StockAddressDataImport\StockAddressDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StockAddressDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group StockAddressDataImportPluginTest
 * Add your own group annotations below this line
 */
class StockAddressDataImportPluginTest extends Unit
{
    protected const TEST_COUNTRY_ISO_CODE = 'DE';
    protected const TEST_STOCK_NAME = 'Test Warehouse';

    /**
     * @var \SprykerTest\Zed\StockAddressDataImport\StockAddressDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportWillImportStockAddressData(): void
    {
        // Arrange
        $this->tester->haveCountry([CountryTransfer::ISO2_CODE => static::TEST_COUNTRY_ISO_CODE]);
        $this->tester->haveStock([StockTransfer::NAME => static::TEST_STOCK_NAME]);

        $dataImportConfigurationTransfer = $this->getDataImporterReaderConfigurationTransfer('import/warehouse_address.csv');

        $stockAddressDataImportPlugin = new StockAddressDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $stockAddressDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertEquals(1, $dataImporterReportTransfer->getImportedDataSetCount());
    }

    /**
     * @return void
     */
    public function testImportWillThrowAnExceptionIfStockDoesNotExist(): void
    {
        // Arrange
        $this->tester->haveCountry([CountryTransfer::ISO2_CODE => static::TEST_COUNTRY_ISO_CODE]);
        $this->tester->haveStock([StockTransfer::NAME => static::TEST_STOCK_NAME]);

        $dataImportConfigurationTransfer = $this->getDataImporterReaderConfigurationTransfer('import/warehouse_address_incorrect_stock_name.csv');
        $dataImportConfigurationTransfer->setThrowException(true);

        $stockAddressDataImportPlugin = new StockAddressDataImportPlugin();

        $this->expectException(DataImportException::class);
        $this->expectErrorMessageMatches('/Warehouse \".+\" not found\./');

        // Act
        $stockAddressDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportWillThrowAnExceptionIfCountryIsoCodeDoesNotExist(): void
    {
        // Arrange
        $this->tester->haveCountry([CountryTransfer::ISO2_CODE => static::TEST_COUNTRY_ISO_CODE]);
        $this->tester->haveStock([StockTransfer::NAME => static::TEST_STOCK_NAME]);

        $dataImportConfigurationTransfer = $this->getDataImporterReaderConfigurationTransfer('import/warehouse_address_incorrect_iso_code.csv');
        $dataImportConfigurationTransfer->setThrowException(true);

        $stockAddressDataImportPlugin = new StockAddressDataImportPlugin();

        $this->expectException(DataImportException::class);

        $this->expectErrorMessageMatches('/Country with ISO2 code \".+\" not found\./');

        // Act
        $stockAddressDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeWillReturnCorrectType(): void
    {
        // Arrange
        $stockAddressDataImportPlugin = new StockAddressDataImportPlugin();

        // Act
        $importType = $stockAddressDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(StockAddressDataImportConfig::IMPORT_TYPE_STOCK_ADDRESS, $importType);
    }

    /**
     * @param string $filePath
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function getDataImporterReaderConfigurationTransfer(string $filePath): DataImporterConfigurationTransfer
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . $filePath);

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        return $dataImportConfigurationTransfer;
    }
}
