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
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Spryker\Zed\ServicePointDataImport\Communication\Plugin\DataImport\ServiceDataImportPlugin;
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
 * @group ServiceDataImportPluginTest
 * Add your own group annotations below this line
 */
class ServiceDataImportPluginTest extends Unit
{
    /**
     * * @uses \Spryker\Zed\ServicePointDataImport\ServicePointDataImportConfig::IMPORT_TYPE_SERVICE
     *
     * @var string
     */
    protected const IMPORT_TYPE_SERVICE = 'service';

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
        $this->tester->haveServicePoint([
            ServicePointTransfer::KEY => 'sp1',
        ]);

        $this->tester->haveServicePoint([
            ServicePointTransfer::KEY => 'sp2',
        ]);

        $this->tester->haveServiceType([
            ServiceTypeTransfer::KEY => 'pickup',
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/service.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $serviceDataImportPlugin = new ServiceDataImportPlugin();
        $dataImporterReportTransfer = $serviceDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertCount(2, $this->tester->getServiceQuery());
    }

    /**
     * @return void
     */
    public function testGetImportPointServiceReturnsExpectedType(): void
    {
        // Arrange
        $serviceDataImportPlugin = new ServiceDataImportPlugin();

        // Act
        $importType = $serviceDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(static::IMPORT_TYPE_SERVICE, $importType);
    }
}
