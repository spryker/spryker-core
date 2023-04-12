<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ServicePointDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\ServicePointDataImport\Communication\Plugin\DataImport\ServicePointDataImportPlugin;
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
 * @group ServicePointDataImportPluginTest
 * Add your own group annotations below this line
 */
class ServicePointDataImportPluginTest extends Unit
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
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/service_point.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $servicePointDataImportPlugin = new ServicePointDataImportPlugin();
        $dataImporterReportTransfer = $servicePointDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertCount(2, $this->tester->getServicePointQuery());
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsExpectedType(): void
    {
        // Arrange
        $servicePointDataImportPlugin = new ServicePointDataImportPlugin();

        // Act
        $importType = $servicePointDataImportPlugin->getImportType();

        // Assert
        $this->assertSame('service-point', $importType);
    }
}
