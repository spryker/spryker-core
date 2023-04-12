<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ServicePointDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ServicePointDataImport\Communication\Plugin\DataImport\ServicePointStoreDataImportPlugin;
use SprykerTest\Zed\ServicePointDataImport\ServicePointDataImportCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group ServicePointStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class ServicePointStoreDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ServicePointDataImport\ServicePointDataImportCommunicationTester
     */
    protected ServicePointDataImportCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureServicePointTablesAreEmpty();
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $this->tester->haveServicePoint([ServicePointTransfer::KEY => 'sp1']);
        $this->tester->haveServicePoint([ServicePointTransfer::KEY => 'sp2']);

        $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->tester->haveStore([StoreTransfer::NAME => 'AT']);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/service_point_store.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $servicePointStoreDataImportPlugin = new ServicePointStoreDataImportPlugin();
        $dataImporterReportTransfer = $servicePointStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertCount(4, $this->tester->getServicePointStoreQuery());
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsExpectedType(): void
    {
        // Arrange
        $servicePointStoreDataImportPlugin = new ServicePointStoreDataImportPlugin();

        // Act
        $importType = $servicePointStoreDataImportPlugin->getImportType();

        // Assert
        $this->assertSame('service-point-store', $importType);
    }
}
