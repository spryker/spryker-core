<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\StoreDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StoreDataImport\Communication\Plugin\DataImport\StoreDataImportPlugin;
use Spryker\Zed\StoreDataImport\StoreDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group StoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class StoreDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\StoreDataImport\StoreDataImportBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testStoreImportImportsData(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/store.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $storeDataImportPlugin = new StoreDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $storeDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccessOrFail(), 'Data import should be finished successfully.');
        $this->assertTrue($this->tester->storeWithNameExists($storeTransfer->getNameOrFail(), 'Store should exist in DB.'));
    }

    /**
     * @return void
     */
    public function testStoreGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $storeDataImportPlugin = new StoreDataImportPlugin();

        // Act
        $importType = $storeDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(StoreDataImportConfig::IMPORT_TYPE_STORE, $importType);
    }
}
