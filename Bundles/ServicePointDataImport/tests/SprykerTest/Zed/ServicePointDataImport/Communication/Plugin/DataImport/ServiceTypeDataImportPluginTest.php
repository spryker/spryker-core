<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ServicePointDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\ServicePointDataImport\Communication\Plugin\DataImport\ServiceTypeDataImportPlugin;
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
 * @group ServiceTypeDataImportPluginTest
 * Add your own group annotations below this line
 */
class ServiceTypeDataImportPluginTest extends Unit
{
    /**
     * * @uses \Spryker\Zed\ServicePointDataImport\ServicePointDataImportConfig::IMPORT_TYPE_SERVICE_TYPE
     *
     * @var string
     */
    protected const IMPORT_TYPE_SERVICE_TYPE = 'service-type';

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
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/service_type.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $serviceTypeDataImportPlugin = new ServiceTypeDataImportPlugin();
        $dataImporterReportTransfer = $serviceTypeDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertCount(1, $this->tester->getServiceTypeQuery());
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsExpectedType(): void
    {
        // Arrange
        $serviceTypeDataImportPlugin = new ServiceTypeDataImportPlugin();

        // Act
        $importType = $serviceTypeDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(static::IMPORT_TYPE_SERVICE_TYPE, $importType);
    }
}
