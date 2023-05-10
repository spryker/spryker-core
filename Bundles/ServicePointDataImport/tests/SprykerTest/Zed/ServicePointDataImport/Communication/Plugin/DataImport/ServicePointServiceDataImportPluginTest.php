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
use Spryker\Zed\ServicePointDataImport\Communication\Plugin\DataImport\ServicePointServiceDataImportPlugin;
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
 * @group ServicePointServiceDataImportPluginTest
 * Add your own group annotations below this line
 */
class ServicePointServiceDataImportPluginTest extends Unit
{
    /**
     * * @uses \Spryker\Zed\ServicePointDataImport\ServicePointDataImportConfig::IMPORT_TYPE_SERVICE_POINT_SERVICE
     *
     * @var string
     */
    protected const IMPORT_TYPE_SERVICE_POINT_SERVICE = 'service-point-service';

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
            ->setFileName(codecept_data_dir() . 'import/service_point_service.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $servicePointServiceDataImportPlugin = new ServicePointServiceDataImportPlugin();
        $dataImporterReportTransfer = $servicePointServiceDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertCount(2, $this->tester->getServicePointServiceQuery());
    }

    /**
     * @return void
     */
    public function testGetImportPointServiceReturnsExpectedType(): void
    {
        // Arrange
        $servicePointServiceDataImportPlugin = new ServicePointServiceDataImportPlugin();

        // Act
        $importType = $servicePointServiceDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(static::IMPORT_TYPE_SERVICE_POINT_SERVICE, $importType);
    }
}
